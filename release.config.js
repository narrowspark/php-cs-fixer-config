module.exports = {
    "branches": [
        "+([0-9])?(.{+([0-9]),x}).x",
        "main",
        "next",
        "next-major",
        {
            "name": "beta",
            "prerelease": true
        },
        {
            "name": "alpha",
            "prerelease": true
        }
    ],
    "dryRun": false,
    "plugins": [
        [
            "@semantic-release/commit-analyzer",
            {
                "preset": "conventionalcommits"
            }
        ],
        [
            "@google/semantic-release-replace-plugin",
            {
                "replacements": [
                    {
                        "files": [
                            "composer.json"
                        ],
                        "from": "\"dev-master\": \".*\"",
                        "to": "\"dev-master\": \"${nextRelease.version.replace(/\\.\\w+$/, '-dev')}\"",
                    }
                ]
            }
        ],
        [
            "@semantic-release/release-notes-generator",
            {
                "preset": "conventionalcommits"
            }
        ],
        "@semantic-release/changelog",
        "@semantic-release/github",
        [
            "@semantic-release/git",
            {
                "assets": [
                    "composer.json",
                    "src/*",
                    "UPGRADE.md",
                    "LICENSE.md",
                    "CHANGELOG.md"
                ],
                "message": "chore(release): ${nextRelease.version} [skip ci]\n\n${nextRelease.notes}"
            }
        ]
    ]
}
