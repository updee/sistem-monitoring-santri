/**
 * IBS Ash-Shiddiiqi — Sistem Monitoring Santri
 * resources/js/app.js
 * Laravel 9 + Bootstrap 5
 */

import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min';
import Chart from 'chart.js/auto';

window.Chart = Chart;

// ══════════════════════════════════════════════════════════════
// SIDEBAR RESPONSIVE TOGGLE
// ══════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {

    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebarOverlay');
    const hamburger = document.getElementById('hamburgerBtn');

    hamburger?.addEventListener('click', () => {
        sidebar?.classList.toggle('open');
        overlay?.classList.toggle('show');
    });
    overlay?.addEventListener('click', () => {
        sidebar?.classList.remove('open');
        overlay?.classList.remove('show');
    });

    // Close sidebar on resize to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) {
            sidebar?.classList.remove('open');
            overlay?.classList.remove('show');
        }
    });

    // ── Auto-hide flash alerts ──────────────────────────────────
    document.querySelectorAll('.alert-auto-hide').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s, transform 0.5s';
            el.style.opacity    = '0';
            el.style.transform  = 'translateY(-8px)';
            setTimeout(() => el.remove(), 500);
        }, 4500);
    });

    // ── Confirm delete forms ────────────────────────────────────
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('submit', (e) => {
            if (!confirm(el.dataset.confirm || 'Apakah Anda yakin?')) {
                e.preventDefault();
            }
        });
    });

    // ── Tooltip initialization (Bootstrap 5) ───────────────────
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });

    // ── Active sidebar item highlight ───────────────────────────
    const currentPath = window.location.pathname;
    document.querySelectorAll('.sidebar-item').forEach(item => {
        if (item.getAttribute('href') === currentPath) {
            item.classList.add('active');
        }
    });

    // ── Preview foto before upload ──────────────────────────────
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        input.addEventListener('change', () => {
            const preview = document.getElementById(input.dataset.preview);
            if (!preview) return;
            const file = input.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => preview.src = e.target.result;
                reader.readAsDataURL(file);
            }
        });
    });

    // ── Auto-calculate hafalan jumlah halaman ───────────────────
    const halDari   = document.getElementById('halDari');
    const halSampai = document.getElementById('halSampai');
    const jmlHal    = document.getElementById('jmlHal');

    if (halDari && halSampai && jmlHal) {
        const hitung = () => {
            const dari   = parseInt(halDari.value)   || 0;
            const sampai = parseInt(halSampai.value) || 0;
            jmlHal.value = (dari && sampai && sampai >= dari) ? (sampai - dari + 1) : '';
        };
        halDari.addEventListener('input', hitung);
        halSampai.addEventListener('input', hitung);
    }

    // ── Auto-grade from nilai ───────────────────────────────────
    const nilaiInput = document.getElementById('nilaiInput');
    const gradeBadge = document.getElementById('gradeBadge');

    if (nilaiInput && gradeBadge) {
        const updateGrade = () => {
            const n = parseFloat(nilaiInput.value);
            gradeBadge.className = 'badge-custom';
            if (isNaN(n) || nilaiInput.value === '') {
                gradeBadge.textContent = '—'; return;
            }
            const [grade, cls] = n >= 90 ? ['A','grade-a'] :
                                 n >= 75 ? ['B','grade-b'] :
                                 n >= 60 ? ['C','grade-c'] : ['D','grade-d'];
            gradeBadge.classList.add(cls);
            gradeBadge.textContent = grade;
        };
        nilaiInput.addEventListener('input', updateGrade);
    }

    // ── Kehadiran bulk: set all status ──────────────────────────
    window.setAllStatus = (status) => {
        document.querySelectorAll(`input[type="radio"][value="${status}"]`)
            .forEach(r => r.checked = true);
        updateAbsensiSummary();
    };

    window.updateAbsensiSummary = () => {
        const counts = { hadir:0, izin:0, sakit:0, alpha:0 };
        document.querySelectorAll('input[type="radio"]:checked').forEach(r => {
            if (counts[r.value] !== undefined) counts[r.value]++;
        });
        Object.keys(counts).forEach(k => {
            const el = document.getElementById('count' + k.charAt(0).toUpperCase() + k.slice(1));
            if (el) el.textContent = counts[k];
        });
    };

    document.querySelectorAll('input[type="radio"][name^="absensi"]').forEach(r =>
        r.addEventListener('change', updateAbsensiSummary)
    );
    updateAbsensiSummary();

    // ── Auto-poin pelanggaran dari kategori ─────────────────────
    const kategoriSelect = document.getElementById('kategoriSelect');
    if (kategoriSelect) {
        window.autoPoin = (sel) => {
            const opt    = sel.options[sel.selectedIndex];
            const poin   = opt.dataset.poin   || '';
            const tingkat= opt.dataset.tingkat || '';
            const pi     = document.getElementById('poinInput');
            const tb     = document.getElementById('tingkatBadge');
            if (pi) pi.value = poin;
            if (tb) {
                tb.className = 'badge-custom';
                const cls = {ringan:'badge-gold',sedang:'badge-purple',berat:'badge-red'}[tingkat] || 'badge-gray';
                tb.classList.add(cls);
                tb.textContent = tingkat ? tingkat.charAt(0).toUpperCase() + tingkat.slice(1) : '—';
            }
        };
    }

    // ── Tanggal kembali min = tanggal mulai ─────────────────────
    const tglMulai   = document.querySelector('input[name="tanggal_mulai"]');
    const tglKembali = document.querySelector('input[name="tanggal_kembali"]');
    if (tglMulai && tglKembali) {
        tglMulai.addEventListener('change', () => {
            tglKembali.min = tglMulai.value;
            if (tglKembali.value && tglKembali.value < tglMulai.value) {
                tglKembali.value = tglMulai.value;
            }
        });
    }

    // ── Print page ──────────────────────────────────────────────
    document.querySelectorAll('[data-action="print"]').forEach(btn => {
        btn.addEventListener('click', () => window.print());
    });

    // ── Search with debounce ────────────────────────────────────
    let searchTimeout;
    document.querySelectorAll('input[data-search-auto]').forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => input.closest('form')?.submit(), 600);
        });
    });

    // ── Number formatter ────────────────────────────────────────
    window.formatNumber = (n) => new Intl.NumberFormat('id-ID').format(n);

});

// ══════════════════════════════════════════════════════════════
// CHART HELPERS (global)
// ══════════════════════════════════════════════════════════════
window.createBarChart = (canvasId, labels, datasets, options = {}) => {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    return new Chart(ctx, {
        type: 'bar',
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, ...options.plugins },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 } }, beginAtZero: true },
                ...options.scales
            },
            ...options
        }
    });
};

window.createDoughnutChart = (canvasId, labels, data, colors) => {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    return new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{ data, backgroundColor: colors, borderWidth: 0, hoverOffset: 4 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '72%',
            plugins: { legend: { display: false } }
        }
    });
};

window.createLineChart = (canvasId, labels, datasets) => {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    return new Chart(ctx, {
        type: 'line',
        data: { labels, datasets },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 } }, beginAtZero: true }
            },
            elements: { line: { tension: 0.3 }, point: { radius: 4 } }
        }
    });
};
