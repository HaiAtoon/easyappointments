# Change Logs

Tracking changes against upstream: [alextselegidis/easyappointments](https://github.com/alextselegidis/easyappointments)

---

### 2026-03-28 | `eeefb506` | Add auto-updating Change Logs with GitHub Action
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:eeefb5066367d2134f023f739ace17987cb4e0de)

- `.github/scripts/generate-changelog-entry.sh` lines:[1-73] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-0d5146fc0b34d8806ffb091380bf2bef9e60b00eb50d8580e7304f6701da0966)]
- `.github/workflows/changelog.yml` lines:[1-57] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-a0aba5a2bd30b2f80a3a18aa24ce43670b1320ca0a8b9c103bb3bc971ae30a85)]
- `.github/workflows/deploy.yml` lines:[35] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-28802fbf11c83a2eee09623fb192785e7ca92a3f40602a517c011b947a1822d3)]
- `.gitignore` lines:[14] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-bc37d034bad564583790a46f19d807abfe519c5671395fd494d8cce506c42947)]
- `CHANGELOG.md` lines:[1-205, 207, 209-210, 212-217, 219, 221-222, 224-229, 231, 233-234, 236, 238, 240-241, 243, 245, 247-248, 250, 252, 254-255, 257-258, 260, 262-263, 265-266, 268] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-06572a96a58dc510037d5efa622f9bec8519bc1beab13c9f251e97e657a9d4ed)]

---


### 2026-03-27 | `bd9ada01` | Add rtl.min.css for production use
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:bd9ada016efafd134f1277d8fd3abb0a1e5fe640)

- `assets/css/rtl.min.css` lines:[1-51] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-f800a0e3d683819eef29fa6963c1f0d9a70223b3ff60a2c777e78c3030bf2e7e)]

---

### 2026-03-27 | `0f379faf` | Fix storage permissions after deploy
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:0f379fafa1306ff38e64a58f40e38c64155316ab)

- `.github/workflows/deploy.yml` lines:[51, 55] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-28802fbf11c83a2eee09623fb192785e7ca92a3f40602a517c011b947a1822d3)]

---

### 2026-03-27 | `4464f324` | Fix config.php exclude to only match root, split storage sync to preserve runtime data
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:4464f3249dd355402e672e42bf2b332f999e495c)

- `.github/workflows/deploy.yml` lines:[30-31, 38-47] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-28802fbf11c83a2eee09623fb192785e7ca92a3f40602a517c011b947a1822d3)]

---

### 2026-03-27 | `f658d34a` | Fix language always using configured value, fix storage deploy rules
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:f658d34a76797c6362875045bb68cab6051d429f)

- `.github/workflows/deploy.yml` lines:[31-34] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-28802fbf11c83a2eee09623fb192785e7ca92a3f40602a517c011b947a1822d3)]
- `application/config/config.php` lines:[134] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-98933efd56c3885925689f9ce8189c831d4a076ee368af92ae0303c9cb29b110)]

---

### 2026-03-27 | `7bb2e38a` | Restart Apache after deploy
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:7bb2e38ac584d2357e46e07840c8ec4e41cab776)

- `.github/workflows/deploy.yml` lines:[40-45] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-28802fbf11c83a2eee09623fb192785e7ca92a3f40602a517c011b947a1822d3)]

---

### 2026-03-27 | `bd7f782c` | Exclude storage/ from deploy, add missing minified files
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:bd7f782c8ed1ab10999d2b333801f999f3897b10)

- `.github/workflows/deploy.yml` lines:[31] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-28802fbf11c83a2eee09623fb192785e7ca92a3f40602a517c011b947a1822d3)]
- `assets/css/general.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-9f24c046ce8c5a842e70868610f99f125105349484c1ac5976386aa77cc50276)]
- `assets/js/app.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-d25b9a5906efaaa509f0486c13b8bc60af8a94ed508f95f0d5ce7882b9d243b4)]

---

### 2026-03-27 | `6cd94a5a` | Include minified JS/CSS files — required for deployment
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:6cd94a5ac42e546e6e2dd71e13f6eab4e08113a9)

- `.gitignore` _(lines removed only)_ [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-bc37d034bad564583790a46f19d807abfe519c5671395fd494d8cce506c42947)]
- `assets/css/components/color_selection.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-f3a79dc85cae34bae591d491d1bc7157e1572bf23456d21e579e3d7c413612f3)]
- `assets/css/layouts/account_layout.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-943140fd427c575c7dba68ada6a31cfd4af5bf453a7c079e408ffec668d60025)]
- `assets/css/layouts/backend_layout.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-75d39f3b144f54fcd2bc58a053f2d93de8162f4e356ebe6de8e8ca280af460c8)]
- `assets/css/layouts/booking_layout.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-5a2767a398aa7083c7d67719e2feb26ea0fc4cc633e9ce743183c12672f4224b)]
- `assets/css/layouts/message_layout.min.css` _(lines removed only)_ [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-097b263b41dd305a8987bf5bc5b483b61bedd0df778e5154145385f16c5d617b)]
- `assets/css/pages/installation.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-e19a0b8d46b642f420e9aa899b9926fdee69ba4e6257e0cd90a4a35e4ec62ef2)]
- `assets/css/pages/update.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-dc70a48a9925d5787c2e27670426efd3aab8ab3578096db3c112ce7496b6570f)]
- `assets/css/themes/cosmo.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-79ec5603b7e14d0a6db45b46a7aee9d80fbd1023d6ec0d8195e076e6ce11a461)]
- `assets/css/themes/darkly.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-1f1d19cf337c1235323308a389416970ed9435ed4d217bb2042a32b6e214c386)]
- `assets/css/themes/default.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-3293a4909bc17b98b176ddd9703964f39e29d4779c3fc2d70afd9fd68050b0a8)]
- `assets/css/themes/flatly.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-8a751f83a67b540712d62c23412277e22fdc9215f8e59dea040c7a77fe3d91cc)]
- `assets/css/themes/litera.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-ace6876c81ebc94f37578d6c8575a1e65890e1dc991c1e3cab67281d8c41a8e1)]
- `assets/css/themes/lumen.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-cbbcad7da649e2eb1118c10ba5db7176cb73ba66455f17ffd2ebbd51fd84967e)]
- `assets/css/themes/materia.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-5a810fa9f7d0014b1dba3b1099b4f2c4266ecd25029b75ca0eb4ad4b361c039d)]
- `assets/css/themes/minty.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-3032b81cee6cce926787efaf974d603bdedfdb6b4b750947e6b76dde90552fe4)]
- `assets/css/themes/sketchy.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-776c79c0149833305b392467eb6d759c039b4975aceac22734d855e6e76504b6)]
- `assets/css/themes/zephyr.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-da521d4887c4a5b6408f071ee1bbf74f854b8e5d5c606cfdd3b908ae61c946f1)]
- `assets/js/components/appointment_status_options.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-3089b18229cd68422328074ce1be2a1ee23d5cd085cccbd09ec1141ddad8e222)]
- `assets/js/components/appointments_modal.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-e7a57c8b2c28298595f1693326ae5b7be05666254e450b4fed713771d2b3dae6)]
- `assets/js/components/color_selection.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-63f8c27c589d5eecbb0c77ab746db2e3a46aa49dca8abc88f6d9585e4054bb25)]
- `assets/js/components/ldap_import_modal.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-943d473427aece0f2d4a9ef640bf7564aeda39e4460990b21dabca0d8392d71c)]
- `assets/js/components/unavailabilities_modal.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-51558a8fd26a15a0fd82ec2dbd8cc79dfe94634edf80e5a7290e33e1ba4f3b59)]
- `assets/js/components/working_plan_exceptions_modal.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-41709d71b9aa4aace072fa92b7741aa2299b51fbb364bf9e50a23937a5b738a4)]
- `assets/js/http/account_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-072a64c72c8db983cdb1c37458af6ee8f8ba34da829c5964f224999e7afac91b)]
- `assets/js/http/admins_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-598eb30659ee6a3358ce40f10241e3e769a3e6183abeb36fcac082395e7884e6)]
- `assets/js/http/api_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-b97825f3ee12fc16c041aa43561ea55eb973862fd66e859f30fbf8afffbb5e2b)]
- `assets/js/http/appointments_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-23940c0d44c96f37aff52436f2ddd0fe0c8a7e872ff2b1a030ba95f635e54ca2)]
- `assets/js/http/blocked_periods_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-943422c99af3acbf7b8ed845ee7c13f19cbd2e7530335153c903c58abc59765a)]
- `assets/js/http/booking_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-d90f8d1cbfd6a61496b3248fca507e378b1c5bef55c4f33aeaaa31ce0050e8da)]
- `assets/js/http/booking_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-42ec325edbd6bdc88f9c199565af182e8ae84060c02262d607d8a91ac4a0d4f0)]
- `assets/js/http/business_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-af5346abe3abbd8af462b4627dd5bd60e16344149ba6f286ac1f3b77ec1c0726)]
- `assets/js/http/caldav_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-cb80c66495114456e5882a8d4a63d5eb3f4e3b15bb2bcb59e588577e5227a7a1)]
- `assets/js/http/calendar_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-4b3e3ad7fc190cea6d56d2e41212d8a5d6e71bd587a0a8287f48a50cc227f7a3)]
- `assets/js/http/customers_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-3a92b336cdc708eff01e122442e676d0ebd9ced0addd88c556b517d0bc0ed40e)]
- `assets/js/http/general_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-feb0eda4a15404ea214f53dd04d60264f790389363902f761c40425811e60599)]
- `assets/js/http/google_analytics_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-8d102d7c23f0943139bada232b107bfd0d5339aae2f4c88037221ff5b695b455)]
- `assets/js/http/google_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-7100b33c5944f6b606921a0019a3f5790257cae71760ea9baebacfc7cb7b0310)]
- `assets/js/http/ldap_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-7c900060bf4afc4ed86c27cdbc470f07ce400569f8207501232f72261d651aaa)]
- `assets/js/http/legal_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-fb9a48481eea71144754c3f6b92418b55bd151892ff554ba07c254799ccf9eec)]
- `assets/js/http/localization_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-f54a54fafa6b9f57d984322b2770b4e95bf4e061e89cb88a4ac3ffe6e2891743)]
- `assets/js/http/login_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-49121ed71eea5a21f7018db484c209586b2d8f220b453a244b2653151abf53cf)]
- `assets/js/http/matomo_analytics_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-bcfe52e651e924e13857e7585130b2671d0e5a10afe937caaaaa1a3c7adf729f)]
- `assets/js/http/providers_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-7524a28c5805f25fcd0653e632214296d1a812836a204a93bb04ad2e3056c193)]
- `assets/js/http/recovery_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-0209d48ff2c80c66aecf24df6554914109ef1c2df1420e4b4cbb8efec7a406d4)]
- `assets/js/http/secretaries_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-215cd99d54a186bc86ebf306983d9f0c9bc375891f4b47e4cbe0d5d32bab40c9)]
- `assets/js/http/service_categories_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-8288b9893c27eb52f1fbb797f272aaf7891965b7539575328970733677f120d6)]
- `assets/js/http/services_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-ed54a90abf3e6833e7e7160f7f8d8476e910cad9d2453fad5e0caee768b0dec8)]
- `assets/js/http/settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-490c49461b1755e6468d87e6f2f2540a078da563e4dda814d035a3284ca453ad)]
- `assets/js/http/unavailabilities_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-935991b27c8ce54eead6fe3d30761278833fca587b2d7196b39dc4e23b9eba34)]
- `assets/js/http/webhooks_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-2eb50d8314c67f4ce9903ab2dd0ba35a8349e5fd98e1295b5cae6e8018ec2224)]
- `assets/js/layouts/account_layout.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-89891b8493dd3f324c1e5d6eb57ce3f754041275ff414855b2138c85d621b078)]
- `assets/js/layouts/backend_layout.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-bddeb28cf2c109254c6d5b1831ae05b3eb024ae725664b0759c33a84c88f404a)]
- `assets/js/layouts/booking_layout.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-332071566c286646f5161e28b2abca2e4731c0c9244888281d157726485cf68d)]
- `assets/js/layouts/message_layout.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-2071c6f46d1c0bfe597f72b1e5f9aa326d320e3c9210f5c6f25fe0ad13b44b33)]
- `assets/js/pages/account.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-bd7ce3bdac187371665cf97ae879915758880449e293d8b82d46886d1e6ee3a8)]
- `assets/js/pages/admins.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-f58333a0f64c55303cd4a677f08df77d0fb327daed5ea9e2826400504d992843)]
- `assets/js/pages/api_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-4c8a5e73fc0109dce8166afaab2a3f17d74d66309a14af1bd5c60a1005ffb8ee)]
- `assets/js/pages/blocked_periods.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-8e3d326479b52b11b160312f2f3647a296379f16ee2dfd138975ca66557fad9e)]
- `assets/js/pages/booking.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-7eb76ffd734460d95b91d5be1ef669a9bad381d98ede4d5f2161477498be5ba1)]
- `assets/js/pages/booking_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-f343dc910e77eb52fcaeb5bc5d0a614d6ef457e66c144d6a28749d10907707bf)]
- `assets/js/pages/business_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-9c0ee998a4d9a99b14d8d0ec88d03dc68f6e3e6f440353922432f8e4ab32741c)]
- `assets/js/pages/calendar.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-eaa1da104b1c6edfe2f305c84a302ead13ca97ce8e13c14df2adcacf8bd8bb71)]
- `assets/js/pages/customers.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-bc1a4ddb4995cdf904d31406ba1988b6d5b47ce338976a6307e08fa55fd34500)]
- `assets/js/pages/general_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-daff48a368fae1025d8638e4ae188ceeedafe4e6d6896a9835cf32ce5fbb936f)]
- `assets/js/pages/google_analytics_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-964ee5b9385541554296d9d2b2227db8dbe5391fd857b514fa0e944dcc1db3e8)]
- `assets/js/pages/installation.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-6dde07a1523d4672aa78c808bd267813adb7b1ce6b1f9d39c08120952541cfe2)]
- `assets/js/pages/ldap_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-96b164aa8e93d5240bf07a0c1a74db740407aa77cabe896f8b0fea691ea92461)]
- `assets/js/pages/legal_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-86f515ef8971e1336be23ad2809bce8598ca6f385ce5c6acd58c26eee6c30877)]
- `assets/js/pages/login.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-9443e5a19c99c0b66fdcc424bbf2c41670eb730b55b525e170cbb2c71fc519ca)]
- `assets/js/pages/matomo_analytics_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-2602ad60799e6e57fc1b9d8299331e6fc9cd3440162349e8d30e2849986493ee)]
- `assets/js/pages/providers.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-6606120085196eaddda56310193a329452148e1f6705f038d919e5cb5373340e)]
- `assets/js/pages/recovery.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-a2ffd4462de4372106ec97e9aa50a8ea5c6d570aff3c4cf8d946060e867d4856)]
- `assets/js/pages/secretaries.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-2daa3ec6634d07257dd115abebccbe93d4bf9ebbad6b5afb0d2d55141eda66ec)]
- `assets/js/pages/service_categories.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-24606c6ac3f9bdad7ba513cc4442a3f8deb23a6d88993d580350b6cd87afebf0)]
- `assets/js/pages/services.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-cb6b2ab277d833e8bd1face78619f268272ca6dee5af25b68aa3aa8b8c90edf4)]
- `assets/js/pages/webhooks.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-03ad9d209c48570334eab6bb4cd7a3f76d7c39574e8be46459b3dd9329f95269)]
- `assets/js/utils/calendar_default_view.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-803b01f04b6c0e46ce3b70abadff2892965a7e22ed986d829c17951fa36773be)]
- `assets/js/utils/calendar_event_popover.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-44d9c0053e3bd311c77cde32268bdd26cd56cfab10c0d4b3a67bf263bf54c73f)]
- `assets/js/utils/calendar_sync.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-46b97dd97d0220a3768e3dfa6ab82d2341ca20727159c32633c78c0181c58c65)]
- `assets/js/utils/calendar_table_view.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-d3d64ad3b905a6a473fa802d565e906a8399e211c72e1e529be8e548276da88a)]
- `assets/js/utils/date.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-efe87df1cb11b816bd8092966946292e644152d350c1200c2bc0cdbc98bbedea)]
- `assets/js/utils/file.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-1e25a5d0b57fb58a478b87f37b6959eba307f719b391ee5d291e177747e9e136)]
- `assets/js/utils/http.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-f43b5bb4429a872f1d7f4918c81a174d93b165f5c68b2fbbb99079002c186d93)]
- `assets/js/utils/lang.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-8ec79db32ae2684bade261bf4827e8c34eb90b488a47ce9ac93166f257a323d0)]
- `assets/js/utils/message.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-4e87130464d1bc30c6e55cae803db0e2432970cd652540cce5a1f1b4f7bd00f9)]
- `assets/js/utils/string.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-f5d3bf5267d0d89fbc6faed84e3010484498285eea75a93b3216a09533434512)]
- `assets/js/utils/ui.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-a3614beac8372f2c173b8aeaf28913e205344583242e35ac1dc9a05cf78f4c77)]
- `assets/js/utils/url.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-c4b39bba3e64debadac0569d258a9ef8cd0d098564771b2c4c216f1b5ec14b22)]
- `assets/js/utils/validation.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-7cb10628446a24bc49b7b6e86881c0f464cad28f1ba6a4f76d8317bd89b42c8f)]
- `assets/js/utils/working_plan.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-b1e3bd91f64cccf9f068ab59a79061ba4cce1e15a6eb15a543ae52b972383f3f)]

---

### 2026-03-27 | `2134ce89` | Move is_rtl to config variable, remove helper function
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:2134ce89df4fbdab048df569baafcbc2c4fdf57d)

- `application/config/config.php` lines:[144-145] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-98933efd56c3885925689f9ce8189c831d4a076ee368af92ae0303c9cb29b110)]
- `application/helpers/language_helper.php` _(lines removed only)_ [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-5c525de26797077bcf58affc5e386bc8ba322ee27c91eea3fdf276af984ab796)]
- `application/views/layouts/account_layout.php` lines:[2, 22, 28] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-4dbe96cc788ef593f23642cbe7f09053b3d2a8d426947b681d71027e6c2596a1)]
- `application/views/layouts/backend_layout.php` lines:[2, 28, 34] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-9a0eb11e673434315918b4e22c6e08a733d6d3fcc5ffd56954804968f19391f3)]
- `application/views/layouts/booking_layout.php` lines:[2, 32, 39] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-789adca6159fded2f2072e5488bf08807ef6d87fc095606fe07c9c2a7c12fcd7)]
- `application/views/layouts/message_layout.php` lines:[2, 24, 30] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-31dac9fb1e6ea197eef3d1db1d7fcf478b1744134b0ec7ae05fdbd32c7ec5758)]
- `application/views/pages/installation.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-6238f1a3cb2dc688cc7408bf917869083ec52d9cb7dca13bac2e621fdc46880e)]
- `application/views/pages/update.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-01e3ebe43aef6c615a7c9bf36accc18d72d3a2a2dd6f2ede333f626e19c7289b)]

---

### 2026-03-27 | `4cd9f1e0` | Extract is_rtl() helper to replace repeated RTL condition
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:4cd9f1e00f7cbe07b3512d82437c5963be06b930)

- `application/helpers/language_helper.php` lines:[40-51] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-5c525de26797077bcf58affc5e386bc8ba322ee27c91eea3fdf276af984ab796)]
- `application/views/layouts/account_layout.php` lines:[2, 22, 28] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-4dbe96cc788ef593f23642cbe7f09053b3d2a8d426947b681d71027e6c2596a1)]
- `application/views/layouts/backend_layout.php` lines:[2, 28, 34] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-9a0eb11e673434315918b4e22c6e08a733d6d3fcc5ffd56954804968f19391f3)]
- `application/views/layouts/booking_layout.php` lines:[2, 32, 39] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-789adca6159fded2f2072e5488bf08807ef6d87fc095606fe07c9c2a7c12fcd7)]
- `application/views/layouts/message_layout.php` lines:[2, 24, 30] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-31dac9fb1e6ea197eef3d1db1d7fcf478b1744134b0ec7ae05fdbd32c7ec5758)]
- `application/views/pages/installation.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-6238f1a3cb2dc688cc7408bf917869083ec52d9cb7dca13bac2e621fdc46880e)]
- `application/views/pages/update.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-01e3ebe43aef6c615a7c9bf36accc18d72d3a2a2dd6f2ede333f626e19c7289b)]

---

### 2026-03-27 | `497e8f06` | RTL: use CSS class approach instead of inline dir attributes
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:497e8f06f9b2c4e5bf8f5a1025dafe3bd75dc300)

- `application/views/components/backend_header.php` lines:[10, 55, 73, 96] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-e5f1f7ca945d1ca890211c90592616b2c21255875188b91185b8a1ee3f9101f0)]
- `application/views/layouts/account_layout.php` lines:[22-25, 28] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-4dbe96cc788ef593f23642cbe7f09053b3d2a8d426947b681d71027e6c2596a1)]
- `application/views/layouts/backend_layout.php` lines:[28-31, 34] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-9a0eb11e673434315918b4e22c6e08a733d6d3fcc5ffd56954804968f19391f3)]
- `application/views/layouts/booking_layout.php` lines:[32-35, 39] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-789adca6159fded2f2072e5488bf08807ef6d87fc095606fe07c9c2a7c12fcd7)]
- `application/views/layouts/message_layout.php` lines:[24-27, 30] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-31dac9fb1e6ea197eef3d1db1d7fcf478b1744134b0ec7ae05fdbd32c7ec5758)]
- `assets/css/rtl.css` lines:[1-51] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-ce5f45b86a30f2ebdcf564ecb7bef87e14641e7011d3409147a33e3effa18a42)]

---

### 2026-03-27 | `d1f59c41` | Add RTL to navbar dropdown menus
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:d1f59c418da115d8cdb3f7ea265297386daef56b)

- `application/views/components/backend_header.php` lines:[8, 56, 74, 97] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-e5f1f7ca945d1ca890211c90592616b2c21255875188b91185b8a1ee3f9101f0)]

---

### 2026-03-27 | `ff8f389c` | Keep navbar LTR layout in RTL mode
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:ff8f389caec270cc7ad00ea1f421ccb0706cb6dc)

- `application/views/components/backend_header.php` lines:[10, 22] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-e5f1f7ca945d1ca890211c90592616b2c21255875188b91185b8a1ee3f9101f0)]

---

### 2026-03-27 | `93a36465` | Fix navbar direction in RTL mode
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:93a36465bfb6bf04caec9daa1e4e5c9d300145a2)

- `application/views/components/backend_header.php` lines:[22] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-e5f1f7ca945d1ca890211c90592616b2c21255875188b91185b8a1ee3f9101f0)]

---

### 2026-03-27 | `74f777ab` | Add RTL support for Hebrew, Arabic, and Persian
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:74f777ab50328221ea10bc2467da5675ad1dc142)

- `application/views/layouts/account_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-4dbe96cc788ef593f23642cbe7f09053b3d2a8d426947b681d71027e6c2596a1)]
- `application/views/layouts/backend_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-9a0eb11e673434315918b4e22c6e08a733d6d3fcc5ffd56954804968f19391f3)]
- `application/views/layouts/booking_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-789adca6159fded2f2072e5488bf08807ef6d87fc095606fe07c9c2a7c12fcd7)]
- `application/views/layouts/message_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-31dac9fb1e6ea197eef3d1db1d7fcf478b1744134b0ec7ae05fdbd32c7ec5758)]
- `application/views/pages/installation.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-6238f1a3cb2dc688cc7408bf917869083ec52d9cb7dca13bac2e621fdc46880e)]
- `application/views/pages/update.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-01e3ebe43aef6c615a7c9bf36accc18d72d3a2a2dd6f2ede333f626e19c7289b)]

---

### 2026-03-27 | `2e94ee03` | Revert RTL changes — breaks CSS loading
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:2e94ee03c873af77f943bbba5b93327c0867cd58)

- `application/views/layouts/account_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-4dbe96cc788ef593f23642cbe7f09053b3d2a8d426947b681d71027e6c2596a1)]
- `application/views/layouts/backend_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-9a0eb11e673434315918b4e22c6e08a733d6d3fcc5ffd56954804968f19391f3)]
- `application/views/layouts/booking_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-789adca6159fded2f2072e5488bf08807ef6d87fc095606fe07c9c2a7c12fcd7)]
- `application/views/layouts/message_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-31dac9fb1e6ea197eef3d1db1d7fcf478b1744134b0ec7ae05fdbd32c7ec5758)]
- `application/views/pages/installation.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-6238f1a3cb2dc688cc7408bf917869083ec52d9cb7dca13bac2e621fdc46880e)]
- `application/views/pages/update.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-01e3ebe43aef6c615a7c9bf36accc18d72d3a2a2dd6f2ede333f626e19c7289b)]

---

### 2026-03-27 | `207f0d05` | Trigger deploy after fixing VM permissions
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:207f0d0590e13fdb2b8a03f4b2c01073dc9ba3fc)

_No file changes_

---

### 2026-03-27 | `82e560ae` | Fix rsync: skip permissions and timestamps on deploy
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:82e560aed0e0a4d3b7b28eb9a4bb8f2834943d7f)

- `.github/workflows/deploy.yml` lines:[29] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-28802fbf11c83a2eee09623fb192785e7ca92a3f40602a517c011b947a1822d3)]

---

### 2026-03-27 | `2ce56377` | Fix rsync permission errors in deploy workflow
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:2ce563773831c2c97b999b6307d07b6dd416b207)

- `.github/workflows/deploy.yml` lines:[29] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-28802fbf11c83a2eee09623fb192785e7ca92a3f40602a517c011b947a1822d3)]

---

### 2026-03-27 | `7d002fec` | Add GitHub Actions deploy workflow for GCP VM
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:7d002fec02a6f781fecc628976e6d54fac7ab836)

- `.github/workflows/deploy.yml` lines:[1-38] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-28802fbf11c83a2eee09623fb192785e7ca92a3f40602a517c011b947a1822d3)]
- `deploy-targets.txt` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-b3f5b1910443bc60720921cb144d57365b0d54340c3d9b2a3b9d5426a4226d25)]

---

### 2026-03-27 | `da81e555` | Remove CLAUDE.md from repo and add to .gitignore
[View Diff vs Upstream](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:da81e55569041761fb154f321048e6524e5e7be8)

- `.gitignore` lines:[15] [[Watch](https://github.com/alextselegidis/easyappointments/compare/main...HaiAtoon/easyappointments:main#diff-bc37d034bad564583790a46f19d807abfe519c5671395fd494d8cce506c42947)]
- `CLAUDE.md` _(deleted)_

---
