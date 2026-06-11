##[debug]Evaluating: secrets.PROD_APP_KEY
##[debug]Evaluating Index:
##[debug]..Evaluating secrets:
##[debug]..=> Object
##[debug]..Evaluating String:
##[debug]..=> 'PROD_APP_KEY'
##[debug]=> '***'
##[debug]Result: '***'
##[debug]Evaluating: secrets.PROD_DB_PASSWORD
##[debug]Evaluating Index:
##[debug]..Evaluating secrets:
##[debug]..=> Object
##[debug]..Evaluating String:
##[debug]..=> 'PROD_DB_PASSWORD'
##[debug]=> '***'
##[debug]Result: '***'
##[debug]Evaluating: secrets.PROD_APP_URL
##[debug]Evaluating Index:
##[debug]..Evaluating secrets:
##[debug]..=> Object
##[debug]..Evaluating String:
##[debug]..=> 'PROD_APP_URL'
##[debug]=> '***'
##[debug]Result: '***'
##[debug]Evaluating: secrets.DB_DATABASE
##[debug]Evaluating Index:
##[debug]..Evaluating secrets:
##[debug]..=> Object
##[debug]..Evaluating String:
##[debug]..=> 'DB_DATABASE'
##[debug]=> '***'
##[debug]Result: '***'
##[debug]Evaluating: secrets.DB_USERNAME
##[debug]Evaluating Index:
##[debug]..Evaluating secrets:
##[debug]..=> Object
##[debug]..Evaluating String:
##[debug]..=> 'DB_USERNAME'
##[debug]=> '***'
##[debug]Result: '***'
##[debug]Evaluating condition for step: 'Deploy to Docker Swarm via SSH'
##[debug]Evaluating: success()
##[debug]Evaluating success:
##[debug]=> true
##[debug]Result: true
##[debug]Starting: Deploy to Docker Swarm via SSH
##[debug]Loading inputs
##[debug]Evaluating: secrets.SSH_HOST
##[debug]Evaluating Index:
##[debug]..Evaluating secrets:
##[debug]..=> Object
##[debug]..Evaluating String:
##[debug]..=> 'SSH_HOST'
##[debug]=> '***'
##[debug]Result: '***'
##[debug]Evaluating: secrets.SSH_USER
##[debug]Evaluating Index:
##[debug]..Evaluating secrets:
##[debug]..=> Object
##[debug]..Evaluating String:
##[debug]..=> 'SSH_USER'
##[debug]=> '***'
##[debug]Result: '***'
##[debug]Evaluating: secrets.SSH_PRIVATE_KEY
##[debug]Evaluating Index:
##[debug]..Evaluating secrets:
##[debug]..=> Object
##[debug]..Evaluating String:
##[debug]..=> 'SSH_PRIVATE_KEY'
##[debug]=> '***
##[debug]***
##[debug]***
##[debug]***
##[debug]***
##[debug]***
##[debug]***'
##[debug]Result: '***
##[debug]***
##[debug]***
##[debug]***
##[debug]***
##[debug]***
##[debug]***'
##[debug]Evaluating: format('cd {0}
##[debug]
##[debug]# Login ke GHCR di dalam VM agar bisa pull image private
##[debug]echo "{1}" | docker login ghcr.io -u {2} --password-stdin
##[debug]
##[debug]# Tarik image terbaru
##[debug]docker pull $APP_IMAGE
##[debug]
##[debug]# Export semua secret sebagai environment variable di terminal VM
##[debug]export PROD_APP_KEY=$PROD_APP_KEY
##[debug]export PROD_DB_PASSWORD=$PROD_DB_PASSWORD
##[debug]export PROD_APP_URL=$PROD_APP_URL
##[debug]export DB_DATABASE=$DB_DATABASE
##[debug]export DB_USERNAME=$DB_USERNAME
##[debug]export APP_IMAGE=$APP_IMAGE
##[debug]
##[debug]# Deploy stack (Swarm akan otomatis membaca environment variables di atas)
##[debug]docker stack deploy -c docker-compose.yml sistem-event
##[debug]', secrets.DEPLOY_PATH, secrets.GHCR_PAT, github.actor)
##[debug]Evaluating format:
##[debug]..Evaluating String:
##[debug]..=> 'cd {0}
##[debug]
##[debug]# Login ke GHCR di dalam VM agar bisa pull image private
##[debug]echo "{1}" | docker login ghcr.io -u {2} --password-stdin
##[debug]
##[debug]# Tarik image terbaru
##[debug]docker pull $APP_IMAGE
##[debug]
##[debug]# Export semua secret sebagai environment variable di terminal VM
##[debug]export PROD_APP_KEY=$PROD_APP_KEY
##[debug]export PROD_DB_PASSWORD=$PROD_DB_PASSWORD
##[debug]export PROD_APP_URL=$PROD_APP_URL
##[debug]export DB_DATABASE=$DB_DATABASE
##[debug]export DB_USERNAME=$DB_USERNAME
##[debug]export APP_IMAGE=$APP_IMAGE
##[debug]
##[debug]# Deploy stack (Swarm akan otomatis membaca environment variables di atas)
##[debug]docker stack deploy -c docker-compose.yml sistem-event
##[debug]'
##[debug]..Evaluating Index:
##[debug]....Evaluating secrets:
##[debug]....=> Object
##[debug]....Evaluating String:
##[debug]....=> 'DEPLOY_PATH'
##[debug]..=> '***'
##[debug]..Evaluating Index:
##[debug]....Evaluating secrets:
##[debug]....=> Object
##[debug]....Evaluating String:
##[debug]....=> 'GHCR_PAT'
##[debug]..=> '***'
##[debug]..Evaluating Index:
##[debug]....Evaluating github:
##[debug]....=> Object
##[debug]....Evaluating String:
##[debug]....=> 'actor'
##[debug]..=> 'ginganomercy'
##[debug]=> 'cd ***
##[debug]
##[debug]# Login ke GHCR di dalam VM agar bisa pull image private
##[debug]echo "***" | docker login ghcr.io -u ginganomercy --password-stdin
##[debug]
##[debug]# Tarik image terbaru
##[debug]docker pull $APP_IMAGE
##[debug]
##[debug]# Export semua secret sebagai environment variable di terminal VM
##[debug]export PROD_APP_KEY=$PROD_APP_KEY
##[debug]export PROD_DB_PASSWORD=$PROD_DB_PASSWORD
##[debug]export PROD_APP_URL=$PROD_APP_URL
##[debug]export DB_DATABASE=$DB_DATABASE
##[debug]export DB_USERNAME=$DB_USERNAME
##[debug]export APP_IMAGE=$APP_IMAGE
##[debug]
##[debug]# Deploy stack (Swarm akan otomatis membaca environment variables di atas)
##[debug]docker stack deploy -c docker-compose.yml sistem-event
##[debug]'
##[debug]Result: 'cd ***
##[debug]
##[debug]# Login ke GHCR di dalam VM agar bisa pull image private
##[debug]echo "***" | docker login ghcr.io -u ginganomercy --password-stdin
##[debug]
##[debug]# Tarik image terbaru
##[debug]docker pull $APP_IMAGE
##[debug]
##[debug]# Export semua secret sebagai environment variable di terminal VM
##[debug]export PROD_APP_KEY=$PROD_APP_KEY
##[debug]export PROD_DB_PASSWORD=$PROD_DB_PASSWORD
##[debug]export PROD_APP_URL=$PROD_APP_URL
##[debug]export DB_DATABASE=$DB_DATABASE
##[debug]export DB_USERNAME=$DB_USERNAME
##[debug]export APP_IMAGE=$APP_IMAGE
##[debug]
##[debug]# Deploy stack (Swarm akan otomatis membaca environment variables di atas)
##[debug]docker stack deploy -c docker-compose.yml sistem-event
##[debug]'
##[debug]Loading env
Run appleboy/ssh-action@v1.0.3
/usr/bin/docker run --name f9f51ac51fc41e60d84c40b6be38d03968e49c_bcba65 --label f9f51a --workdir /github/workspace --rm -e "REGISTRY" -e "IMAGE_NAME" -e "PROD_APP_KEY" -e "PROD_DB_PASSWORD" -e "PROD_APP_URL" -e "DB_DATABASE" -e "DB_USERNAME" -e "APP_IMAGE" -e "INPUT_HOST" -e "INPUT_USERNAME" -e "INPUT_KEY" -e "INPUT_ENVS" -e "INPUT_SCRIPT" -e "INPUT_PORT" -e "INPUT_PASSPHRASE" -e "INPUT_PASSWORD" -e "INPUT_SYNC" -e "INPUT_USE_INSECURE_CIPHER" -e "INPUT_CIPHER" -e "INPUT_TIMEOUT" -e "INPUT_COMMAND_TIMEOUT" -e "INPUT_KEY_PATH" -e "INPUT_FINGERPRINT" -e "INPUT_PROXY_HOST" -e "INPUT_PROXY_PORT" -e "INPUT_PROXY_USERNAME" -e "INPUT_PROXY_PASSWORD" -e "INPUT_PROXY_PASSPHRASE" -e "INPUT_PROXY_TIMEOUT" -e "INPUT_PROXY_KEY" -e "INPUT_PROXY_KEY_PATH" -e "INPUT_PROXY_FINGERPRINT" -e "INPUT_PROXY_CIPHER" -e "INPUT_PROXY_USE_INSECURE_CIPHER" -e "INPUT_SCRIPT_STOP" -e "INPUT_ENVS_FORMAT" -e "INPUT_DEBUG" -e "INPUT_ALLENVS" -e "INPUT_REQUEST_PTY" -e "HOME" -e "GITHUB_JOB" -e "GITHUB_REF" -e "GITHUB_SHA" -e "GITHUB_REPOSITORY" -e "GITHUB_REPOSITORY_OWNER" -e "GITHUB_REPOSITORY_OWNER_ID" -e "GITHUB_RUN_ID" -e "GITHUB_RUN_NUMBER" -e "GITHUB_RETENTION_DAYS" -e "GITHUB_RUN_ATTEMPT" -e "GITHUB_ACTOR_ID" -e "GITHUB_ACTOR" -e "GITHUB_WORKFLOW" -e "GITHUB_HEAD_REF" -e "GITHUB_BASE_REF" -e "GITHUB_EVENT_NAME" -e "GITHUB_SERVER_URL" -e "GITHUB_API_URL" -e "GITHUB_GRAPHQL_URL" -e "GITHUB_REF_NAME" -e "GITHUB_REF_PROTECTED" -e "GITHUB_REF_TYPE" -e "GITHUB_WORKFLOW_REF" -e "GITHUB_WORKFLOW_SHA" -e "GITHUB_REPOSITORY_ID" -e "GITHUB_TRIGGERING_ACTOR" -e "GITHUB_WORKSPACE" -e "GITHUB_ACTION" -e "GITHUB_EVENT_PATH" -e "GITHUB_ACTION_REPOSITORY" -e "GITHUB_ACTION_REF" -e "GITHUB_PATH" -e "GITHUB_ENV" -e "GITHUB_STEP_SUMMARY" -e "GITHUB_STATE" -e "GITHUB_OUTPUT" -e "RUNNER_DEBUG" -e "RUNNER_OS" -e "RUNNER_ARCH" -e "RUNNER_NAME" -e "RUNNER_ENVIRONMENT" -e "RUNNER_TOOL_CACHE" -e "RUNNER_TEMP" -e "RUNNER_WORKSPACE" -e "ACTIONS_RUNTIME_URL" -e "ACTIONS_RUNTIME_TOKEN" -e "ACTIONS_CACHE_URL" -e "ACTIONS_RESULTS_URL" -e "ACTIONS_ORCHESTRATION_ID" -e GITHUB_ACTIONS=true -e CI=true -v "/var/run/docker.sock":"/var/run/docker.sock" -v "/home/runner/work/_temp":"/github/runner_temp" -v "/home/runner/work/_temp/_github_home":"/github/home" -v "/home/runner/work/_temp/_github_workflow":"/github/workflow" -v "/home/runner/work/_temp/_runner_file_commands":"/github/file_commands" -v "/home/runner/work/sistem-event-kampus/sistem-event-kampus":"/github/workspace" f9f51a:c51fc41e60d84c40b6be38d03968e49c
======CMD======
cd ***

# Login ke GHCR di dalam VM agar bisa pull image private
echo "***" | docker login ghcr.io -u ginganomercy --password-stdin

# Tarik image terbaru
docker pull $APP_IMAGE

# Export semua secret sebagai environment variable di terminal VM
export PROD_APP_KEY=$PROD_APP_KEY
export PROD_DB_PASSWORD=$PROD_DB_PASSWORD
export PROD_APP_URL=$PROD_APP_URL
export DB_DATABASE=$DB_DATABASE
export DB_USERNAME=$DB_USERNAME
export APP_IMAGE=$APP_IMAGE

# Deploy stack (Swarm akan otomatis membaca environment variables di atas)
docker stack deploy -c docker-compose.yml sistem-event

======END======
out: Login Succeeded
out: latest: Pulling from nisrinasalwamuadzah-ui/sistem-event-kampus
out: 164d9c374eb3: Pulling fs layer
out: 72532a990daa: Pulling fs layer
out: a1265bc2a95f: Pulling fs layer
out: a1265bc2a95f: Download complete
out: 72532a990daa: Download complete
out: a1265bc2a95f: Pull complete
out: 72532a990daa: Pull complete
out: 164d9c374eb3: Download complete
out: 164d9c374eb3: Pull complete
out: Digest: sha256:84db2401c18ece27dabe4d66b5ab79ba2d6538a4a31a5d60efe5e22705e41aa7
out: Status: Downloaded newer image for ghcr.io/nisrinasalwamuadzah-ui/sistem-event-kampus:latest
out: ghcr.io/nisrinasalwamuadzah-ui/sistem-event-kampus:latest
err: Ignoring unsupported options: build
err: Since --detach=false was not specified, tasks will be created in the background.
err: In a future release, --detach=false will become the default.
err: network "ingress_network" is declared as external, but could not be found. You need to create a swarm-scoped network before the stack is deployed
2026/06/02 09:42:15 Process exited with status 1
##[debug]Docker Action run completed with exit code 1
##[debug]Finishing: Deploy to Docker Swarm via SSH