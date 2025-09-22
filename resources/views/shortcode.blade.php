<style>
    /* Fullscreen overlay */
    #fhm-loading {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        font-size: 1.2rem;
        font-weight: 500;
        color: #333;
    }

    /* Spinner */
    #fhm-loading::before {
        content: "";
        width: 24px;
        height: 24px;
        border: 3px solid #999;
        border-top-color: transparent;
        border-radius: 50%;
        display: inline-block;
        animation: spin 0.8s linear infinite;
        margin-right: 10px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<div id="fhm-heatmap" style="position: relative;">
    <!-- Loading overlay (will be removed after first load) -->
    <div id="fhm-loading">Loading heatmap...</div>

    <table id="fhm-table" class="heatmap-table" style="width:100%">
        <thead>
            <tr id="fhm-header">
                <th>Pair</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    (async function() {

        const FH_CONFIG = {
            restUrl: 'http://test.local/index.php?rest_route=/fhm/v1/data',
            updateInterval: 5
        };
        const timeframe_names = {
            1: "1 Min",
            5: "5 Min",
            15: "15 Min",
            30: "30 Min",
            60: "1 Hour",
            240: "4 Hour",
            1440: "Daily",
            10080: "Weekly",
            43200: "Monthly",
        };

        // First fetch: only to get the timeframes so we can build headers
        const res = await fetch(FH_CONFIG.restUrl);
        const initialData = await res.json();
        const firstPair = Object.keys(initialData)[0];
        const timeframes = Object.keys(initialData[firstPair]);

        // Build table headers dynamically
        const headerRow = document.getElementById("fhm-header");
        timeframes.forEach(tf => {
            const th = document.createElement("th");
            th.textContent = timeframe_names[tf] ?? tf;
            headerRow.appendChild(th);
        });

        // Initialize DataTable
        let fhtable = new DataTable('#fhm-table', {
            ajax: function(data, callback) {
                fetch(FH_CONFIG.restUrl)
                    .then(res => res.json())
                    .then(json => {
                        const rows = Object.entries(json).map(([pair, tfData]) => {
                            const row = [pair];
                            const isJPY = pair.toUpperCase().includes("JPY");

                            timeframes.forEach(tf => {
                                const open = parseFloat(tfData[tf].open);
                                const close = parseFloat(tfData[tf].close);

                                // multiplier depends on pair
                                const multiplier = isJPY ? 100 : 10000;
                                const change = (close - open) * multiplier;

                                row.push(change);
                            });
                            return row;
                        });

                        callback({
                            data: rows
                        });
                    })
                    .catch(err => {
                        console.error("Error fetching heatmap data:", err);
                        callback({
                            data: []
                        });
                    });
            },
            stateSave: true,
            paging: true,
            searching: true,
            ordering: true,
            columnDefs: [{
                targets: "_all",
                createdCell: function(td, cellData, rowData, row, col) {
                    if (col === 0) return; // skip Pair column

                    td.textContent = parseFloat(cellData).toFixed(2);

                    td.classList.remove("heatmap-positive", "heatmap-negative",
                        "heatmap-neutral");
                    if (cellData > 0) {
                        td.classList.add("heatmap-positive");
                    } else if (cellData < 0) {
                        td.classList.add("heatmap-negative");
                    } else {
                        td.classList.add("heatmap-neutral");
                    }
                }
            }]
        });

        // Remove loading overlay after first draw
        fhtable.on('draw', function() {
            const loader = document.getElementById("fhm-loading");
            if (loader) loader.remove();
        });

        // Auto-refresh without resetting pagination/search
        setInterval(() => {
            fhtable.ajax.reload(null, false);
        }, FH_CONFIG.updateInterval * 1000);
    })();
</script>
