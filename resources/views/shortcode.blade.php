<div id="fh-heatmap-root" data-fh-attrs='@json($fhm_atts)' class="fh-heatmap">

    <div class="fh-controls">
        <label>View:
            <select id="fh-view-select">
                <option value="percent" @if ($fhm_atts['view'] === 'percent') selected @endif>Percent (%)</option>
                <option value="pips" @if ($fhm_atts['view'] === 'pips') selected @endif>Pips</option>
            </select>
        </label>

        <label>Filter:
            <input id="fh-filter" placeholder="e.g. USD, EUR" />
        </label>

        <button id="fh-sort-btn" data-order="desc">Sort by strength ↓</button>
    </div>

    <div id="fh-heatmap-container">Loading...</div>
</div>
