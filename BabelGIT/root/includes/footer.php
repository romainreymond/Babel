         <script>
			$(document).ready(function() {
				if ($.cookie('sidebar_close') === '1') {
					$('body').addClass('sidebar-close');
				}
				else {
					$('body').addClass('sidebar-open');        
				}
                $('.truncate').succinct({
                    size: 1000
                });
				$('.truncate100').succinct({
                    size: 100
                });
                App.init();
			});
			$(window).load(function() {
				$(function(){
					function centerImageVertically() {
						var imgframes = $('.frame img');
						imgframes.each(function(i){
							var imgVRelativeOffset = ($(this).height() - $(this).parent().height()) / 2;
							$(this).css({
								'position': 'absolute',
								'top': imgVRelativeOffset * -1
							});
						});
						var imgframes2 = $('.frame2 img');
						imgframes2.each(function(i){
							var imgVRelativeOffset2 = ($(this).height() - $(this).parent().height()) / 2;
							$(this).css({
								'position': 'absolute',
								'top': imgVRelativeOffset2 * -1
							});
						});
					}
					centerImageVertically();
					$(window).resize(function() {
						centerImageVertically();
					});
				});
			});
        </script>
    </body>
</html>