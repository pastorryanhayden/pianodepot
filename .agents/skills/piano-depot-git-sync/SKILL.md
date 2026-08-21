---
name: piano-depot-git-sync
description: Synchronize, preview, and publish changes whenever working in the Piano Depot repository. Pull the current origin/main and start the local PHP preview before editing, then validate and commit completed changes. Pushing main triggers CI/CD deployment to the hosted Forge site and requires explicit user approval.
---

# Piano Depot Git Sync

Use the Git repository containing this skill as the working repository. Resolve its path with `git rev-parse --show-toplevel`; do not assume a machine-specific checkout location. Do not edit a protected backup copy or create a duplicate checkout.

## Before changing files

1. Confirm the repository root, current branch, remote URL, and working-tree status.
2. Require `main` and `origin` set to the SSH URL `git@github.com:pastorryanhayden/pianodepot.git`. Use the user's terminal SSH authentication for fetches and pushes; do not open GitHub Desktop. Stop and report any mismatch.
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
3. When checks pass, stage only the task files and create a concise descriptive commit.
4. A push to `origin main` triggers the CI/CD pipeline and updates the hosted Forge site. Treat every push as a live deployment: show the user the completed local result and obtain explicit approval immediately before pushing, unless the user's current request explicitly says to push or publish.
5. Never force-push. If the push is rejected because the remote advanced, fetch and integrate safely; rerun checks before pushing. Stop for direction if there is a conflict or unrelated divergence.
6. After an approved push, verify that local `main` matches `origin/main`, verify the Forge site updated successfully, then report the commit hash, checks run, pushed files, and hosted-site status.

The repository owner's standing instruction authorizes local commits for changes Codex makes while completing a requested task in this repository. It does not authorize a push unless the current request explicitly says to push or publish, because the CI/CD pipeline deploys `main` to the hosted Forge site. It also does not authorize changes to the legacy WordPress site, deletion, repair, or inclusion of unrelated local changes. If a requested task is inspection-only and makes no file changes, do not create an empty commit or push.
