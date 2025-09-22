<div class="wrap">
    <h1>{{ __('Forex Heatmap', 'FHM') }}</h1>

    <h2>{{ __('Settings', 'FHM') }}</h2>
    <form method="post" action="{{ admin_url('admin-post.php') }}">
        {!! $nonce_field !!}

        <input type="hidden" name="action" value="fhm_save_settings">

        <table class="form-table">
            <tr>
                <th><label for="update_interval">{{ __('Update interval (seconds)', 'FHM') }}</label></th>
                <td><input type="number" min="5" max="120" name="ui_update_interval" id="update_interval"
                        value="{{ $options['ui_update_interval'] }}"></td>
            </tr>
            <tr>
                <th><label for="external_api_url">{{ __('Api URL', 'FHM') }}</label></th>
                <td><input type="url" name="external_api_url" id="external_api_url"
                        value="{{ $options['external_api_url'] }}"></td>
            </tr>
        </table>

        <?php submit_button(__('Save Settings', 'FHM')); ?>
    </form>
    <hr>

    <h2>{{ __('Internal Endpoint Status', 'FHM') }}</h2>
    <p>{{ __('Address:', 'FHM') }} <code>{{ $endpoint_url }}</code><br></p>
    <p>{{ __('Status', 'FHM') }}: <strong id="endpoint-status" class="status"> {{ $endpoint_status }}</strong></p>
    <button id="check-endpoint-btn" class="button button-primary button-large">{{ __('Test now', 'FHM') }}</button>
    <hr>

    <h2>{{ __('External API Status', 'FHM') }}</h2>
    <p>{{ __('Address:', 'FHM') }} <code>{{ $external_api_url }}</code><br></p>
    <p>{{ __('Status', 'FHM') }}: <strong id="api-status" class="status"> {{ $endpoint_status }}</strong></p>
    <div>
        <pre id="api-status-response"></pre>
    </div>
    <button id="check-api-btn" class="button button-primary button-large">{{ __('Test now', 'FHM') }}</button>
</div>
