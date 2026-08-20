<style>
/* PWA Bottom Sheet Notification */
.pwa-bottom-sheet {
    position: fixed;
    bottom: -150%;
    left: 0;
    width: 100%;
    background: #ffffff;
    box-shadow: 0 -5px 25px rgba(0,0,0,0.15);
    border-radius: 24px 24px 0 0;
    z-index: 9999;
    padding: 24px 20px;
    transition: bottom 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    display: flex;
    flex-direction: column;
    align-items: center;
    box-sizing: border-box;
}

.pwa-bottom-sheet.show {
    bottom: 0;
}

.pwa-header {
    display: flex;
    align-items: center;
    gap: 16px;
    width: 100%;
    margin-bottom: 16px;
}

.pwa-logo {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    object-fit: contain;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.pwa-text-content {
    flex: 1;
}

.pwa-title {
    font-size: 16px;
    font-weight: bold;
    color: #1e293b;
    margin: 0 0 4px 0;
}

.pwa-desc {
    font-size: 13px;
    color: #64748b;
    margin: 0;
    line-height: 1.4;
}

.pwa-actions {
    display: flex;
    gap: 12px;
    width: 100%;
}

.pwa-btn {
    flex: 1;
    padding: 12px;
    border-radius: 12px;
    font-weight: bold;
    font-size: 14px;
    text-align: center;
    cursor: pointer;
    border: none;
    transition: background 0.2s;
}

.pwa-btn-later {
    background: #f1f5f9;
    color: #64748b;
}

.pwa-btn-install {
    background: #2563eb;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.pwa-btn-update {
    background: #10b981;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

/* iOS Specific Instructions */
.pwa-ios-instructions {
    display: none;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    padding: 12px;
    width: 100%;
    margin-bottom: 16px;
    font-size: 13px;
    color: #1e40af;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.pwa-ios-instructions svg {
    height: 18px;
}
</style>

<!-- Install Prompt -->
<div id="pwaInstallPrompt" class="pwa-bottom-sheet">
    <div class="pwa-header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Event" class="pwa-logo">
        <div class="pwa-text-content">
            <h4 class="pwa-title">Instal App Sistem Event</h4>
            <p class="pwa-desc">Akses secepat kilat tanpa perlu membuka browser. Hemat kuota dan memori HP Anda!</p>
        </div>
    </div>
    
    <div id="iosInstruction" class="pwa-ios-instructions">
        <span>1. Tekan </span>
        <svg viewBox="0 0 50 50" width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb; margin-bottom:-4px;"><path d="M25 35V15M15 25l10-10 10 10M12 25H8v17h34V25h-4"/></svg>
        <span>&nbsp;2. Pilih <b>"Add to Home Screen"</b></span>
    </div>

    <div class="pwa-actions">
        <button id="pwaBtnLater" class="pwa-btn pwa-btn-later">Nanti Saja</button>
        <button id="pwaBtnInstall" class="pwa-btn pwa-btn-install">Instal Sekarang</button>
    </div>
</div>

<!-- Update Prompt -->
<div id="pwaUpdatePrompt" class="pwa-bottom-sheet">
    <div class="pwa-header">
        <div style="background: #ecfdf5; padding: 10px; border-radius: 12px; margin-right: 12px;">
            <i class="ph-bold ph-arrows-clockwise" style="font-size: 32px; color: #10b981;"></i>
        </div>
        <div class="pwa-text-content">
            <h4 class="pwa-title">Versi Baru Tersedia!</h4>
            <p class="pwa-desc">Sistem Event telah diperbarui. Segarkan aplikasi sekarang untuk mendapatkan fitur terbaru.</p>
        </div>
    </div>
    <div class="pwa-actions">
        <button id="pwaBtnUpdate" class="pwa-btn pwa-btn-update">Segarkan Sekarang</button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    let deferredPrompt;
    let newWorker;
    
    const pwaPrompt = document.getElementById('pwaInstallPrompt');
    const btnInstall = document.getElementById('pwaBtnInstall');
    const btnLater = document.getElementById('pwaBtnLater');
    const iosInstruction = document.getElementById('iosInstruction');
    
    const updatePrompt = document.getElementById('pwaUpdatePrompt');
    const btnUpdate = document.getElementById('pwaBtnUpdate');
    
    // --- 1. SERVICE WORKER REGISTRATION & UPDATE DETECTOR ---
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js?v=999').then(reg => {
                // Deteksi jika ada pembaruan service worker yang ditemukan
                reg.addEventListener('updatefound', () => {
                    newWorker = reg.installing;
                    newWorker.addEventListener('statechange', () => {
                        // Jika SW baru sudah selesai diunduh dan menunggu diaktifkan
                        if (newWorker.state === 'installed') {
                            if (navigator.serviceWorker.controller) {
                                // Ini adalah update (bukan install pertama kali)
                                updatePrompt.classList.add('show');
                            }
                        }
                    });
                });
            });
            
            // Auto reload pada controllerchange DIHAPUS karena menyebabkan infinite loop
            // ketika versi SW dipaksa bypass cache.
        });
    }

    // Tombol Update ditekan
    btnUpdate.addEventListener('click', () => {
        if (newWorker) {
            updatePrompt.classList.remove('show');
            // Kirim pesan ke SW baru untuk skipWaiting
            newWorker.postMessage({ type: 'SKIP_WAITING' });
        }
    });


    // --- 2. INSTALL PROMPT LOGIC ---
    const pwaDismissed = localStorage.getItem('pwaDismissedTime');
    const now = new Date().getTime();
    
    // Deteksi iOS Safari & Standalone Mode
    const isIos = () => /iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());
    const isInStandaloneMode = () => ('standalone' in window.navigator && window.navigator.standalone) 
                                   || window.matchMedia('(display-mode: standalone)').matches;

    function showInstallPrompt() {
        if (isInStandaloneMode()) return;
        if (pwaDismissed && now - parseInt(pwaDismissed) < 259200000) return; // Hide for 3 days
        
        setTimeout(() => {
            pwaPrompt.classList.add('show');
        }, 1500); // Munculkan lebih cepat (1.5 detik)
    }

    // Tangkap event bawaan browser jika ada
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
    });

    // Sesuaikan UI untuk iOS, lalu SELALU panggil pop-up (agar muncul saat baru buka web)
    if (isIos() && !isInStandaloneMode()) {
        iosInstruction.style.display = 'flex';
        btnInstall.style.display = 'none';
    }
    showInstallPrompt(); 

    // Aksi Tombol Instal
    btnInstall.addEventListener('click', async () => {
        if (deferredPrompt) {
            pwaPrompt.classList.remove('show');
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            deferredPrompt = null;
        } else {
            // Fallback jika browser belum memicu event instalasi (misal di Desktop)
            alert("Untuk menginstal manual:\nKlik ikon Menu (titik tiga ⁝) di sudut kanan atas browser Anda, lalu pilih 'Install app' atau 'Add to Home screen'.");
            pwaPrompt.classList.remove('show');
        }
    });

    // Aksi Tombol Nanti Saja
    btnLater.addEventListener('click', () => {
        pwaPrompt.classList.remove('show');
        localStorage.setItem('pwaDismissedTime', now.toString());
    });
    
    window.addEventListener('appinstalled', () => {
        pwaPrompt.classList.remove('show');
    });
});
</script>
