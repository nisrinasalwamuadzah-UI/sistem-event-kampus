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

<script>
document.addEventListener("DOMContentLoaded", () => {
    let deferredPrompt;
    const pwaPrompt = document.getElementById('pwaInstallPrompt');
    const btnInstall = document.getElementById('pwaBtnInstall');
    const btnLater = document.getElementById('pwaBtnLater');
    const iosInstruction = document.getElementById('iosInstruction');
    
    // Mengecek status Do Not Disturb di localStorage
    const pwaDismissed = localStorage.getItem('pwaDismissedTime');
    const now = new Date().getTime();
    
    // Jika user pernah klik Nanti Saja dalam 3 hari terakhir (259200000 ms), hentikan
    if (pwaDismissed && now - parseInt(pwaDismissed) < 259200000) {
        return; 
    }

    // Deteksi iOS Safari
    const isIos = () => {
        const userAgent = window.navigator.userAgent.toLowerCase();
        return /iphone|ipad|ipod/.test(userAgent);
    }
    
    // Deteksi mode Standalone (Apakah aplikasi sudah diinstal?)
    const isInStandaloneMode = () => {
        return ('standalone' in window.navigator) && (window.navigator.standalone) 
            || window.matchMedia('(display-mode: standalone)').matches;
    }

    // Tampilkan Prompt dengan Delay 3 detik
    function showPrompt() {
        if (isInStandaloneMode()) return; // Jangan tampilkan jika sudah diinstal
        
        setTimeout(() => {
            pwaPrompt.classList.add('show');
        }, 3000);
    }

    // Event penangkapan instalasi bawaan Chrome/Edge
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent default mini-infobar
        e.preventDefault();
        // Simpan event untuk dipicu nanti
        deferredPrompt = e;
        showPrompt();
    });

    // Jika perangkat adalah iOS, event beforeinstallprompt tidak akan pernah terpanggil, 
    // jadi kita panggil showPrompt secara manual
    if (isIos() && !isInStandaloneMode()) {
        iosInstruction.style.display = 'flex';
        btnInstall.style.display = 'none'; // Sembunyikan tombol install karena iOS butuh aksi manual
        showPrompt();
    }

    // Tombol Instal Ditekan (Hanya untuk Chrome/Edge/Android)
    btnInstall.addEventListener('click', async () => {
        if (deferredPrompt) {
            pwaPrompt.classList.remove('show');
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                console.log('User accepted the PWA prompt');
            }
            deferredPrompt = null;
        }
    });

    // Tombol Nanti Saja Ditekan
    btnLater.addEventListener('click', () => {
        pwaPrompt.classList.remove('show');
        localStorage.setItem('pwaDismissedTime', now.toString());
    });
    
    // Deteksi instalasi sukses (misalnya user instal dari menu browser)
    window.addEventListener('appinstalled', () => {
        pwaPrompt.classList.remove('show');
        console.log('PWA was installed');
    });
});
</script>
