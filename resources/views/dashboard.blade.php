<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rebel Communication · Agent Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="shell">
        <header class="topbar">
            <div class="brand">
                <div class="brand-mark" aria-hidden="true">RC</div>
                <div>
                    <h1>Rebel Communication</h1>
                    <p>Agent Dashboard</p>
                </div>
            </div>
            <div class="status-pill">System Online</div>
        </header>

        <section class="page-intro">
            <h2>Customer Call Submission</h2>
            <p>Enter the customer's verified information and check live bid availability.</p>
        </section>

        <div class="grid">
            <section class="panel">
                <div class="panel-head">
                    <div class="icon-badge" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20 21a8 8 0 0 0-16 0"/>
                            <circle cx="12" cy="8" r="4"/>
                        </svg>
                    </div>
                    <div>
                        <h3>Customer Information</h3>
                        <p>Enter verified customer details.</p>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="submission-form" autocomplete="off">
                        <div class="field">
                            <label for="phone">Customer Phone Number</label>
                            <div class="input-wrap">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.6a2 2 0 0 1-.5 2.1L8.1 9.6a16 16 0 0 0 6.3 6.3l1.2-1.2a2 2 0 0 1 2.1-.5c.8.3 1.7.6 2.6.7A2 2 0 0 1 22 16.9z"/></svg>
                                <input id="phone" name="phone" type="tel" inputmode="numeric" placeholder="15551234567" required>
                            </div>
                        </div>

                        <div class="field-row">
                            <div class="field">
                                <label for="first_name">First Name</label>
                                <div class="input-wrap">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="8" r="4"/></svg>
                                    <input id="first_name" name="first_name" type="text" placeholder="John">
                                </div>
                            </div>
                            <div class="field">
                                <label for="last_name">Last Name</label>
                                <div class="input-wrap">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="8" r="4"/></svg>
                                    <input id="last_name" name="last_name" type="text" placeholder="Doe">
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label for="zip_code">ZIP Code</label>
                            <div class="input-wrap">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s7-5.3 7-12a7 7 0 1 0-14 0c0 6.7 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                <input id="zip_code" name="zip_code" type="text" inputmode="numeric" placeholder="75001" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="traffic_source">Traffic Source</label>
                            <div class="chip-field">
                                <span class="chip">
                                    <input id="traffic_source" name="traffic_source" type="text" value="{{ $defaultTrafficSource }}">
                                </span>
                            </div>
                        </div>

                        <div class="actions">
                            <button class="btn-submit" id="submit-btn" type="submit">Submit</button>
                            <div class="secure-note">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
                                Customer information is processed securely
                            </div>
                            <div class="form-error" id="form-error"></div>
                        </div>
                    </form>
                </div>
            </section>

            <section class="panel">
                <div class="panel-head">
                    <div class="icon-badge sky" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9c.3.6.9 1 1.6 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/></svg>
                    </div>
                    <div>
                        <h3>Bid Result</h3>
                        <p>Real-time bid and routing response.</p>
                    </div>
                </div>
                <div class="panel-body" id="result-panel">
                    <div class="result-empty" id="result-empty">
                        <div>
                            <div class="orb" aria-hidden="true">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.6a2 2 0 0 1-.5 2.1L8.1 9.6a16 16 0 0 0 6.3 6.3l1.2-1.2a2 2 0 0 1 2.1-.5c.8.3 1.7.6 2.6.7A2 2 0 0 1 22 16.9z"/></svg>
                            </div>
                            <h4>No submission yet</h4>
                            <p>Submit customer information to receive the live bidding response.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <section class="panel history-wrap">
            <div class="panel-head">
                <div class="icon-badge" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                </div>
                <div>
                    <h3>Ping History</h3>
                    <p>Recent submissions</p>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>ZIP</th>
                                <th>Status</th>
                                <th>Bid</th>
                                <th>Transfer Number</th>
                            </tr>
                        </thead>
                        <tbody id="history-body">
                            @forelse ($history as $row)
                                <tr>
                                    <td>{{ $row->customer_name }}</td>
                                    <td>{{ $row->phone }}</td>
                                    <td>{{ $row->zip_code }}</td>
                                    <td><span class="badge {{ $row->status }}">{{ $row->status }}</span></td>
                                    <td>{{ $row->bid_amount !== null ? '$'.number_format((float) $row->bid_amount, 2) : '—' }}</td>
                                    <td>{{ $row->transfer_number ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr class="empty-placeholder">
                                    <td colspan="6" class="empty-row">No history yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <script>
        (() => {
            const form = document.getElementById('submission-form');
            const submitBtn = document.getElementById('submit-btn');
            const formError = document.getElementById('form-error');
            const resultPanel = document.getElementById('result-panel');
            const historyBody = document.getElementById('history-body');
            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            const money = (value) => {
                if (value === null || value === undefined || value === '') return '—';
                return '$' + Number(value).toFixed(2);
            };

            const escapeHtml = (value) => String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#39;');

            const renderResult = (result) => {
                const status = result.status || 'error';
                const reason = result.reject_reason
                    ? `<p class="muted">${escapeHtml(result.reject_reason)}</p>`
                    : '';
                const expire = result.expire_in_seconds
                    ? `<div class="metric"><span>Expires In</span><strong>${escapeHtml(result.expire_in_seconds)}s</strong></div>`
                    : '';

                resultPanel.innerHTML = `
                    <div class="result-card">
                        <div class="status-banner ${escapeHtml(status)}">
                            <span>${escapeHtml(status)}</span>
                            <span>${escapeHtml(result.bid_id || 'No bid id')}</span>
                        </div>
                        <div class="metrics">
                            <div class="metric"><span>Bid Amount</span><strong>${money(result.bid_amount)}</strong></div>
                            <div class="metric"><span>Transfer Number</span><strong>${escapeHtml(result.transfer_number || '—')}</strong></div>
                            <div class="metric"><span>Customer</span><strong>${escapeHtml(result.customer || '—')}</strong></div>
                            <div class="metric"><span>Phone</span><strong>${escapeHtml(result.phone || '—')}</strong></div>
                            ${expire}
                        </div>
                        ${reason}
                    </div>
                `;
            };

            const renderHistory = (rows) => {
                if (!rows.length) {
                    historyBody.innerHTML = `<tr class="empty-placeholder"><td colspan="6" class="empty-row">No history yet</td></tr>`;
                    return;
                }

                historyBody.innerHTML = rows.map((row) => `
                    <tr>
                        <td>${escapeHtml(row.customer)}</td>
                        <td>${escapeHtml(row.phone)}</td>
                        <td>${escapeHtml(row.zip_code)}</td>
                        <td><span class="badge ${escapeHtml(row.status)}">${escapeHtml(row.status)}</span></td>
                        <td>${money(row.bid_amount)}</td>
                        <td>${escapeHtml(row.transfer_number || '—')}</td>
                    </tr>
                `).join('');
            };

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                formError.classList.remove('show');
                formError.textContent = '';
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting…';

                const payload = Object.fromEntries(new FormData(form).entries());

                try {
                    const response = await fetch(@json(route('submit')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify(payload),
                    });

                    const data = await response.json();

                    if (!response.ok || !data.ok) {
                        const message = data.message
                            || (data.errors ? Object.values(data.errors).flat().join(' ') : null)
                            || 'Submission failed.';
                        throw new Error(message);
                    }

                    renderResult(data.result);
                    renderHistory(data.history || []);
                } catch (error) {
                    formError.textContent = error.message || 'Submission failed.';
                    formError.classList.add('show');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit';
                }
            });
        })();
    </script>
</body>
</html>
