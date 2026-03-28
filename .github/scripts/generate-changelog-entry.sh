#!/bin/bash
set -euo pipefail

UPSTREAM_REPO="alextselegidis/easyappointments"
FORK_REPO="HaiAtoon/easyappointments"
UPSTREAM_BRANCH="main"

commit="${1:?Usage: generate-changelog-entry.sh <commit-hash>}"

short_hash=$(git log -1 --format="%h" "$commit")
full_hash=$(git log -1 --format="%H" "$commit")
date=$(git log -1 --format="%ad" --date=short "$commit")
message=$(git log -1 --format="%s" "$commit")

compare_url="https://github.com/${UPSTREAM_REPO}/compare/${UPSTREAM_BRANCH}...${FORK_REPO}:${full_hash}"

echo "### ${date} | \`${short_hash}\` | ${message}"
echo "[View Diff vs Upstream](${compare_url})"
echo ""

diff_output=$(git show --unified=0 --format="" "$commit" 2>/dev/null || true)

if [[ -z "$diff_output" ]]; then
    echo "_No file changes_"
    echo ""
    echo "---"
    exit 0
fi

current_file=""
ranges=""
is_deleted=""

flush_file() {
    if [[ -n "$current_file" && -n "$ranges" ]]; then
        file_hash=$(printf '%s' "$current_file" | sha256sum | cut -d' ' -f1)
        watch_url="https://github.com/${UPSTREAM_REPO}/compare/${UPSTREAM_BRANCH}...${FORK_REPO}:${UPSTREAM_BRANCH}#diff-${file_hash}"
        echo "- \`${current_file}\` lines:[${ranges}] [[Watch](${watch_url})]"
    elif [[ -n "$current_file" && "$is_deleted" == "true" ]]; then
        echo "- \`${current_file}\` _(deleted)_"
    elif [[ -n "$current_file" ]]; then
        file_hash=$(printf '%s' "$current_file" | sha256sum | cut -d' ' -f1)
        watch_url="https://github.com/${UPSTREAM_REPO}/compare/${UPSTREAM_BRANCH}...${FORK_REPO}:${UPSTREAM_BRANCH}#diff-${file_hash}"
        echo "- \`${current_file}\` _(lines removed only)_ [[Watch](${watch_url})]"
    fi
}

while IFS= read -r line; do
    if [[ "$line" =~ ^diff\ --git\ a/(.+)\ b/(.+) ]]; then
        flush_file
        current_file="${BASH_REMATCH[2]}"
        ranges=""
        is_deleted=""
    elif [[ "$line" =~ ^deleted\ file\ mode ]]; then
        is_deleted="true"
    elif [[ "$line" =~ ^@@.*\+([0-9]+)(,([0-9]+))?.*@@ ]]; then
        start=${BASH_REMATCH[1]}
        count=${BASH_REMATCH[3]:-1}
        [[ "$count" -eq 0 ]] && continue
        [[ -n "$ranges" ]] && ranges+=", "
        if [[ "$count" -eq 1 ]]; then
            ranges+="$start"
        else
            end=$((start + count - 1))
            ranges+="${start}-${end}"
        fi
    fi
done <<< "$diff_output"

flush_file

echo ""
echo "---"
