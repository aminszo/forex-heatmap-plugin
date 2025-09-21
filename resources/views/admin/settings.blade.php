<div class="wrap">
    <h1>Forex Heatmap Settings</h1>

    <form method="post" action="{{ admin_url('admin-post.php') }}">
        {!! $nonce_field !!}

        <input type="hidden" name="action" value="fhm_save_settings">

        <table class="form-table">
            <tr>
                <th><label for="update_interval">Update interval (seconds)</label></th>
                <td><input type="number" name="ui_update_interval" id="update_interval" value="{{ $options['ui_update_interval'] }}"></td>
            </tr>
            <tr>
                <th><label for="cache_lifetime">Cache lifetime (seconds)</label></th>
                <td><input type="number" name="cache_lifetime" id="cache_lifetime" value="{{ $options['cache_lifetime'] }}"></td>
            </tr>
        </table>

        <?php submit_button('Save Settings'); ?>
    </form>
    <h2>API Endpoint Status</h2>
    <p>
        Endpoint: <code>{{ $endpoint_url }}</code><br>
        Status: <strong> {{$endpoint_status}}</strong>
    </p>

</div>
