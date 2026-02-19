<script>
    (function () {
        if (window.xrayResultsRelease) return;

        function escapeHtml(s) {
            return String(s ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function fmtDateTime(s) {
            const d = new Date(String(s || '').replace(' ', 'T'));
            if (Number.isNaN(d.getTime())) return '';
            return d.toLocaleString([], { year: 'numeric', month: 'short', day: '2-digit', hour: 'numeric', minute: '2-digit' });
        }

        function priorityChip(priority) {
            const p = String(priority || '').toLowerCase();
            if (p === 'stat') return { cls: 'bg-red-100 text-red-800', label: 'STAT' };
            if (p === 'urgent') return { cls: 'bg-amber-100 text-amber-800', label: 'Urgent' };
            return { cls: 'bg-slate-100 text-slate-800', label: p ? p[0].toUpperCase() + p.slice(1) : '-' };
        }

        function setText(id, val) {
            const el = document.getElementById(id);
            if (!el) return;
            el.textContent = (val === null || val === undefined || val === '') ? '-' : String(val);
        }

        function setHtml(id, html) {
            const el = document.getElementById(id);
            if (!el) return;
            el.innerHTML = html;
        }

        async function loadXrayResultsRelease() {
            const res = await fetch('api/xray/results_release.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return json;
        }

        async function loadXrayResult(id) {
            const params = new URLSearchParams();
            params.set('id', String(id || ''));
            const res = await fetch('api/xray/result.php?' + params.toString(), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return json;
        }

        function openModal() {
            const modal = document.getElementById('xrayResultModal');
            if (!modal) return;
            modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('xrayResultModal');
            if (!modal) return;
            modal.classList.add('hidden');
        }

        let boundModal = false;
        function bindModalOnce() {
            if (boundModal) return;
            const modal = document.getElementById('xrayResultModal');
            if (!modal) return;

            boundModal = true;

            const modalClose = document.getElementById('xrayResultClose');
            if (modalClose) modalClose.addEventListener('click', closeModal);

            modal.addEventListener('click', function (e) {
                const t = e.target;
                if (t && t === modal) closeModal();
            });
            modal.querySelectorAll(':scope > div').forEach(function (d, idx) {
                if (idx === 0) {
                    d.addEventListener('click', closeModal);
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal();
            });
        }

        async function showResult(id) {
            const json = await loadXrayResult(id);
            if (!json) {
                return;
            }

            const order = json.order || {};
            const report = json.report || {};
            const img = (json.sample_image_url || '').toString();

            setText('xrayResultTitle', (order.patient_name ? (order.patient_name + ' — ' + (order.exam_type || '')) : 'X-ray Result'));
            setText('xrayResultPatient', order.patient_name);
            setText('xrayResultExam', order.exam_type);
            setText('xrayResultStatus', order.status);
            setText('xrayResultOrdered', fmtDateTime(order.ordered_at));
            setText('xrayReportRadiologist', report.radiologist);
            setHtml('xrayReportHistory', escapeHtml(report.clinical_history || '-'));
            setHtml('xrayReportFindings', escapeHtml(report.findings || '-'));
            setHtml('xrayReportImpression', escapeHtml(report.impression || '-'));

            const imgEl = document.getElementById('xrayResultImg');
            if (imgEl) {
                imgEl.src = img;
            }

            bindModalOnce();
            openModal();
        }

        async function render() {
            const tbody = document.getElementById('xrayReleaseTbody');
            if (!tbody) return;

            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td></tr>';
            const json = await loadXrayResultsRelease();
            if (!json) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-red-600">Unable to load results.</td></tr>';
                return;
            }

            const rows = Array.isArray(json.results) ? json.results : [];
            if (rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No completed studies.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(function (r) {
                const pr = priorityChip(r.priority);
                return (
                    '<tr class="hover:bg-gray-50">' +
                        '<td class="px-6 py-4 text-sm text-gray-900 font-medium">' + escapeHtml(r.patient_name) + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-700">' + escapeHtml(r.exam_type) + '</td>' +
                        '<td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full ' + pr.cls + '">' + escapeHtml(pr.label) + '</span></td>' +
                        '<td class="px-6 py-4 text-sm text-gray-600">' + escapeHtml(fmtDateTime(r.completed_at)) + '</td>' +
                        '<td class="px-6 py-4 text-right">' +
                            '<button type="button" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="window.xrayResultsRelease.showResult(' + Number(r.id) + ')">View</button>' +
                        '</td>' +
                    '</tr>'
                );
            }).join('');
        }

        window.xrayResultsRelease = {
            render: render,
            showResult: showResult,
            closeModal: closeModal,
            bindModalOnce: bindModalOnce,
        };
    })();
</script>
