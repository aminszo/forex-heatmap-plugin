jQuery(document).ready(function ($) {
    const test_api = (buttonId, action, statusElementId, ResponseElementId) => {

        $(`#${buttonId}`).on("click", function () {
            const statusEl = $(`#${statusElementId}`);
            const responseEl = $(`#${ResponseElementId}`);
            $.ajax({
                url: fhData.adminUrl,
                method: "POST",
                data: {
                    action: action
                },
                beforeSend: function () {
                    statusEl.text("...").attr("class", "");
                },
                success: function (response) {
                    responseEl
                        .text(JSON.stringify(response.data, null, 2))
                        .show();
                    if (response.success) {
                        statusEl.text("OK").attr("class", "success");
                    } else {
                        statusEl.text("Fail").attr("class", "fail");
                    }
                },
                error: function () {
                    statusEl.text("Fail").attr("class", "fail");
                }
            });
        });
    };

    test_api('check-api-btn', 'fhm_test_api', 'api-status', 'api-status-response');
    test_api('check-endpoint-btn', 'fhm_test_endpoint', 'endpoint-status', 'endpoint-status-response');
});