---
name: piano-depot-git-sync
description: Synchronize, preview, and publish changes whenever working in the Piano Depot repository. Pull the current origin/main and start the local PHP preview before editing, then validate, commit, and push task changes to origin after editing. Do not use for a protected backup copy or for live-site deployment.
---

# Piano Depot Git Sync

Use the Git repository containing this skill as the working repository. Resolve its path with `git rev-parse --show-toplevel`; do not assume a machine-specific checkout location. Do not edit a protected backup copy or create a duplicate checkout.

## Before changing files

1. Confirm the repository root, current branch, remote URL, and working-tree status.
2. Require `main` and `origin` set to `https://github.com/pastorryanhayden/pianodepot.git`. Stop and report any mismatch.
3. If unrelated local changes or an unresolved merge are present, preserve them and stop for the user's direction.
4. Fetch `origin`, then update with `git pull --ff-only origin main`. Never use a destructive reset or force pull. If the pull cannot fast-forward, stop and explain the divergence.
5. Recheck status before editing.
6. Make the local preview available at `http://localhost:8006`:
   - First check whether that address is already serving this repository. Reuse it when healthy; do not start a duplicate listener.
   - Otherwise, from the repository root, start the PHP development server with the available PHP executable and `-S localhost:8006 router.php`. Prefer `php` from `PATH`; on Apple Silicon Homebrew systems, `/opt/homebrew/bin/php` may be used.
   - Keep the server available while the user reviews changes and verify the local URL responds before reporting that preview is ready.
   - If port 8006 belongs to another process, do not stop that process without permission; report the conflict.
   - Give the user the clickable local URL. Opening a GUI browser requires the user's approval when the environment requests it.

## After making a change

1. Review the diff and include only files changed for the current user request. Never discard, overwrite, stage, or commit unrelated user work.
2. Run the repository's relevant checks. For ordinary PHP/site changes, run `php tests/run.php` using the available PHP executable and run `git diff --check`. Add focused syntax or behavioral checks when appropriate.
3. When checks pass, stage only the task files, create a concise descriptive commit, and push the current `main` commit to `origin main`.
4. Never force-push. If the push is rejected because the remote advanced, fetch and integrate safely; rerun checks before pushing. Stop for direction if there is a conflict or unrelated divergence.
5. Verify that local `main` matches `origin/main`, then report the commit hash, checks run, and pushed files.

The repository owner's standing instruction authorizes commits and pushes for changes Codex makes while completing a requested task in this repository. It does not authorize live deployment, hosting changes, deletion, repair of the legacy WordPress site, or inclusion of unrelated local changes. If a requested task is inspection-only and makes no file changes, do not create an empty commit or push.
