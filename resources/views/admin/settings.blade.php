<div class="wrap">
    <h1>{{ __('Forex Heatmap Settings', 'FHM') }}</h1>

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
                <th><label for="cache_lifetime">{{ __('Cache lifetime (seconds)', 'FHM') }}</label></th>
                <td><input disabled type="number" name="cache_lifetime" id="cache_lifetime"
                        value="{{ $options['cache_lifetime'] }}"></td>
            </tr>

            <tr>
                <th><label for="external_api_url">{{ __('Api URL', 'FHM') }}</label></th>
                <td><input type="url" name="external_api_url" id="external_api_url"
                        value="{{ $options['external_api_url'] }}"></td>
            </tr>

        </table>

        <?php submit_button(__('Save Settings', 'FHM')); ?>
    </form>

    <h2>{{ __('Internal Endpoint Status', 'FHM') }}</h2>
    <p>
        {{ __('Address:', 'FHM') }} <code>{{ $endpoint_url }}</code><br>
    </p>
    <p>
        {{ __('Status', 'FHM') }}: <strong id="endpoint-status"> {{ $endpoint_status }}</strong>
    </p>
    <button id="check-endpoint-btn" class="button button-primary button-large">{{ __('Test now', 'FHM') }}</button>



</div>

<style>
    #endpoint-status {
        padding: 3px 10px;
    }

    .success {
        color: green;
        background-color: rgba(0, 203, 0, 0.3);
    }

    .fail {
        color: #b20000;
        background-color: #ffb6b6;
    }
</style>

<script>
    jQuery(document).ready(function($) {
        const epStatus = $("#endpoint-status")
        $("#check-endpoint-btn").on("click", function() {
            $.ajax({
                url: "{{ admin_url('admin-post.php') }}",
                method: "POST",
                data: {
                    action: "fhm_test_endpoint"
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        epStatus.text("OK");
                        epStatus.attr("class", "success");

                    } else {
                        epStatus.text("Fail");
                        epStatus.attr("class", "fail");
                    }
                },
                error: function() {
                    epStatus.text("Fail");
                    epStatus.attr("class", "fail");
                }
            });
        });
    });
</script>
