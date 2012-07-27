      <hr>

      <footer>
        <p>&copy; Timelinetool 2012</p>
      </footer>

    </div><!--/.container-->

{include file='_asset.modal.tpl'}

    <!-- The javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{$path.js}/core.js"></script>
    <script type="text/javascript">
      var login_form_template = Handlebars.compile($("#login-form-template").html());

      // the assets update buttons
      $('#js-login-button').click(function() {
        $('#myModal .modal-body').html(login_form_template());
        $('#myModal .modal-header h3').html('{$title|string_format:$lang.global.login}');
        $('#myModal #form-save').click(function() {
          // get the data
          var data = {};
          $.each($('#myModal .modal-body form').serializeArray(), function(index, item){
              data[item.name] = item.value;
          });
          // send to server
          login(data, function() {
            $('#myModal').modal('hide');
            window.location.reload();
          }, function() {
            /* TODO some error message */
            $('#myModal').modal('hide');
          });
        });
        $('#myModal').modal( { 'backdrop':'static' } );
      });
      // the assets update buttons
      $('#js-logout-button').click(function() {
        // send to server
        logout(function() {
          window.location.reload();
        }, function() {
          /* TODO some error message */
        });
      });
    </script>
  </body>
</html>
