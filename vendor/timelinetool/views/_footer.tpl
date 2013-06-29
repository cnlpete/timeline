      <hr>

      <footer>
        <p>&copy; Timelinetool 2012</p>
      </footer>

    </div><!--/.container-->

    <!-- The javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{$path.js}/core.js"></script>
    <script type="text/javascript">
      if (supportsFullscreen())
        $('#nav-fullscreen').click(function(e) {
          e.preventDefault();
          toggleFullscreen();
        });
      else
        $('#nav-fullscreen').hide();

      // the login button
      $('#js-login-button').click(function() {
        $('#myLoginModal form').on('submit', function(event) {
          event.preventDefault();
          // disable the form and show a loading thingie
          /* $('#myLoginModal form').disable(); */
          // get the data
          var data = { };
          $.each($('#myLoginModal form').serializeArray(), function(index, item){
              data[item.name] = item.value;
          } );
          if (!data.password)
            data.password = '';
          // send to server
          login(data, function() {
            $('#myLoginModal').modal('hide');
            window.location.reload();
          }, function() {
            /* TODO some error message */
            $('#myLoginModal').modal('hide');
          } );
        });
        $('#myLoginModal').modal( { 'backdrop':'static' } );
      } );

      // the logout button
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
