#!/bin/bash
set -euo pipefail

UPSTREAM_REPO="alextselegidis/easyappointments"
FORK_REPO="HaiAtoon/easyappointments"
UPSTREAM_BRANCH="main"
UPSTREAM_CACHE="/tmp/ea-upstream-files.txt"

commit="${1:?Usage: generate-changelog-entry.sh <commit-hash>}"

if [[ ! -f "$UPSTREAM_CACHE" ]]; then
    curl -sf "https://api.github.com/repos/${UPSTREAM_REPO}/git/trees/${UPSTREAM_BRANCH}?recursive=1" \
        ${GITHUB_TOKEN:+-H "Authorization: Bearer $GITHUB_TOKEN"} \
        | grep '"path"' | sed 's/.*"path": "//;s/".*//' > "$UPSTREAM_CACHE"
fi

file_exists_upstream() {
    grep -qx "$1" "$UPSTREAM_CACHE"
}

short_hash=$(git log -1 --format="%h" "$commit")
full_hash=$(git log -1 --format="%H" "$commit")
date=$(git log -1 --format="%ad" --date=short "$commit")
message=$(git log -1 --format="%s" "$commit")

commit_url="https://github.com/${FORK_REPO}/commit/${full_hash}"

echo "### ${date} | \`${short_hash}\` | ${message}"
echo "[View Commit](${commit_url})"
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
    if [[ -z "$current_file" ]]; then
        return
    fi

    if [[ "$is_deleted" == "true" ]]; then
        echo "- \`${current_file}\` _(deleted)_"
        return
    fi

    local suffix=""
    if [[ -n "$ranges" ]]; then
        suffix=" lines:[${ranges}]"
    else
        suffix=" _(lines removed only)_"
    fi

    if file_exists_upstream "$current_file"; then
        local file_hash
        file_hash=$(printf '%s' "$current_file" | sha256sum | cut -d' ' -f1)
        local watch_url="https://github.com/${FORK_REPO}/compare/upstream...main#diff-${file_hash}"
        echo "- \`${current_file}\`${suffix} [[Watch](${watch_url})]"
    else
        echo "- \`${current_file}\`${suffix} \`[New File]\`"
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
