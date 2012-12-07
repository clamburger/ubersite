<h2>ÜberSite Tools</h2>
<form method="post" action="" class="nomargin" id="ubersite-tools">
    <div class="log"></div>
    <button data-role="setupExport" class="btn btn-inverse btn-large">Push all table schemas to setup/database.sql</button>
    <br>This will increment the current revision number. Ensure you are up to date before doing this.
    <br>You only need to use this if you've updated the database schema locally.
</form>

<script type="text/javascript">
    $('ubersite-tools').select('button[data-role]').invoke('observe', 'click', function (event) {
        event.stop();

        var form = this.up('form');
        var data = form.serialize(true);

        form.disable();
        clear_messages(form);

        data.action = this.getAttribute('data-role');

        new Ajax.Request('index.php?a=setupExport', {
            parameters: data,
            onSuccess: function (transport) {
                form.enable();

                var response = transport.responseText.evalJSON();

                if (response.error) {
                    return render_messages('error', form, response.error);
                }

                render_messages('success', form, response.message);

                Effect.ScrollTo('log', {duration: 0.2});
            }
        });
    });
</script>