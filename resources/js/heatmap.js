(async function () {
    const container = document.getElementById('fhm-heatmap');

    async function fetchData() {
        try {
            const res = await fetch(FH_CONFIG.restUrl);
            const data = await res.json();
            return data;
        } catch (err) {
            console.error('Error fetching heatmap data:', err);
            return null;
        }
    }

    function renderHeatmap(data) {
        // Clear container

        if (!data) {
            console.log("Data is not valid")
            return
        };

        container.innerHTML = '';

        const table = document.createElement('table');
        table.id = "fhm-table";
        table.classList.add('heatmap-table');
        table.style.borderCollapse = 'collapse';
        table.style.width = '100%';

        // Create table header (timeframes)
        const timeframes = Object.keys(data[Object.keys(data)[0]]); // take first pair
        const thead = document.createElement('thead');
        const trHead = document.createElement('tr');
        trHead.innerHTML = '<th>Pair</th>';

        const timeframe_names = {
            1: "1 Min",
            5: "5 Min",
            15: "15 Min",
            30: "30 Min",
            60: "1 Hour",
            240: "$ Hour",
            1440: "Daily",
            10080: "Weekly",
            43200: "Monthly",
        };

        timeframes.forEach(tf => {
            trHead.innerHTML += `<th>${timeframe_names[tf]}</th>`;
        });
        thead.appendChild(trHead);
        table.appendChild(thead);

        // Create table body
        const tbody = document.createElement('tbody');
        for (let pair in data) {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${pair}</td>`;

            timeframes.forEach(tf => {
                const open = parseFloat(data[pair][tf].open);
                const close = parseFloat(data[pair][tf].close);
                const change = (close - open) * 10000; // pip change

                let className = 'heatmap-neutral';

                if (change > 0) {
                    className = 'heatmap-positive';
                } else if (change < 0) {
                    className = 'heatmap-negative';
                }

                tr.innerHTML += `<td class="${className}">${change.toFixed(2)}</td>`;
            });

            tbody.appendChild(tr);
        }

        table.appendChild(tbody);
        container.appendChild(table);

        let fhtable = new DataTable('#fhm-table');
    }

    // Initial load
    let heatmapData = await fetchData();
    renderHeatmap(heatmapData);

    // Auto-update every minute
    setInterval(async () => {
        heatmapData = await fetchData();
        renderHeatmap(heatmapData);
    }, 50000);
})();
