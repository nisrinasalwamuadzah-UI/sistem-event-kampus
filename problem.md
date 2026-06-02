##[debug]Evaluating condition for step: 'Copy Compose file to VM'
##[debug]Evaluating: success()
##[debug]Evaluating success:
##[debug]=> true
##[debug]Result: true
##[debug]Starting: Copy Compose file to VM
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
##[debug]Evaluating: secrets.DEPLOY_PATH
##[debug]Evaluating Index:
##[debug]..Evaluating secrets:
##[debug]..=> Object
##[debug]..Evaluating String:
##[debug]..=> 'DEPLOY_PATH'
##[debug]=> '***'
##[debug]Result: '***'
##[debug]Loading env
Run appleboy/scp-action@v0.1.7
/usr/bin/docker run --name e06b6a79a6e88cf2344ec8917de555c328ccc_2eac04 --label 3e06b6 --workdir /github/workspace --rm -e "REGISTRY" -e "IMAGE_NAME" -e "INPUT_HOST" -e "INPUT_USERNAME" -e "INPUT_KEY" -e "INPUT_SOURCE" -e "INPUT_TARGET" -e "INPUT_PORT" -e "INPUT_PASSWORD" -e "INPUT_TIMEOUT" -e "INPUT_COMMAND_TIMEOUT" -e "INPUT_KEY_PATH" -e "INPUT_PASSPHRASE" -e "INPUT_FINGERPRINT" -e "INPUT_USE_INSECURE_CIPHER" -e "INPUT_RM" -e "INPUT_DEBUG" -e "INPUT_STRIP_COMPONENTS" -e "INPUT_OVERWRITE" -e "INPUT_TAR_DEREFERENCE" -e "INPUT_TAR_TMP_PATH" -e "INPUT_TAR_EXEC" -e "INPUT_PROXY_HOST" -e "INPUT_PROXY_PORT" -e "INPUT_PROXY_USERNAME" -e "INPUT_PROXY_PASSWORD" -e "INPUT_PROXY_PASSPHRASE" -e "INPUT_PROXY_TIMEOUT" -e "INPUT_PROXY_KEY" -e "INPUT_PROXY_KEY_PATH" -e "INPUT_PROXY_FINGERPRINT" -e "INPUT_PROXY_USE_INSECURE_CIPHER" -e "HOME" -e "GITHUB_JOB" -e "GITHUB_REF" -e "GITHUB_SHA" -e "GITHUB_REPOSITORY" -e "GITHUB_REPOSITORY_OWNER" -e "GITHUB_REPOSITORY_OWNER_ID" -e "GITHUB_RUN_ID" -e "GITHUB_RUN_NUMBER" -e "GITHUB_RETENTION_DAYS" -e "GITHUB_RUN_ATTEMPT" -e "GITHUB_ACTOR_ID" -e "GITHUB_ACTOR" -e "GITHUB_WORKFLOW" -e "GITHUB_HEAD_REF" -e "GITHUB_BASE_REF" -e "GITHUB_EVENT_NAME" -e "GITHUB_SERVER_URL" -e "GITHUB_API_URL" -e "GITHUB_GRAPHQL_URL" -e "GITHUB_REF_NAME" -e "GITHUB_REF_PROTECTED" -e "GITHUB_REF_TYPE" -e "GITHUB_WORKFLOW_REF" -e "GITHUB_WORKFLOW_SHA" -e "GITHUB_REPOSITORY_ID" -e "GITHUB_TRIGGERING_ACTOR" -e "GITHUB_WORKSPACE" -e "GITHUB_ACTION" -e "GITHUB_EVENT_PATH" -e "GITHUB_ACTION_REPOSITORY" -e "GITHUB_ACTION_REF" -e "GITHUB_PATH" -e "GITHUB_ENV" -e "GITHUB_STEP_SUMMARY" -e "GITHUB_STATE" -e "GITHUB_OUTPUT" -e "RUNNER_DEBUG" -e "RUNNER_OS" -e "RUNNER_ARCH" -e "RUNNER_NAME" -e "RUNNER_ENVIRONMENT" -e "RUNNER_TOOL_CACHE" -e "RUNNER_TEMP" -e "RUNNER_WORKSPACE" -e "ACTIONS_RUNTIME_URL" -e "ACTIONS_RUNTIME_TOKEN" -e "ACTIONS_CACHE_URL" -e "ACTIONS_RESULTS_URL" -e "ACTIONS_ORCHESTRATION_ID" -e GITHUB_ACTIONS=true -e CI=true -v "/var/run/docker.sock":"/var/run/docker.sock" -v "/home/runner/work/_temp":"/github/runner_temp" -v "/home/runner/work/_temp/_github_home":"/github/home" -v "/home/runner/work/_temp/_github_workflow":"/github/workflow" -v "/home/runner/work/_temp/_runner_file_commands":"/github/file_commands" -v "/home/runner/work/sistem-event-kampus/sistem-event-kampus":"/github/workspace" 3e06b6:a79a6e88cf2344ec8917de555c328ccc
drone-scp version: v1.6.14
tar all files into /tmp/pDWyjiRBPG.tar.gz
remote server os type is unix
scp file to server.
2026/06/02 07:53:11 error copy file to dest: ***, error message: ssh: handshake failed: ssh: unable to authenticate, attempted methods [none publickey], no supported methods remain
drone-scp error: error copy file to dest: ***, error message: ssh: handshake failed: ssh: unable to authenticate, attempted methods [none publickey], no supported methods remain

##[debug]Docker Action run completed with exit code 1
##[debug]Finishing: Copy Compose file to VM