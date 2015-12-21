if ($.fn.liteUploader) {
    jQuery(document).ready(function($) {    
        $('.postfiles').liteUploader(
		{
			script: './dropplets/includes/uploader.php',
			maxSizeInBytes: 1048576,
			typeMessage: '',
			before: function ()
			{
				$('#details').html('');
				$('#response').html('Uploading...');
			},
			each: function (file, errors)
			{
				var i, errorsDisp = '';

				if (errors.length > 0)
				{
					$('#response').html('One or more files did not pass validation');

					for (i = 0; i < errors.length; i++)
					{
						errorsDisp += '<br />' + errors[i].message;
					}
				}

				$('#details').append('<p>Name: ' + file.name + ' Type: ' + file.type + ' Size:' + file.size + errorsDisp + '</p>');
			},
			success: function (response)
			{
				$('#dp-uploaded').html(response);
				window.setTimeout(function(){location.reload()},2000)
			}
		});
    });
}
    jQuery(document).ready(function($) {
        $(".dp-open").click(function(){
            var myelement = $(this).attr("href")
            $(myelement).animate({left:"0"}, 200);
            $.cookies.set('dp-panel', 'open');
            $("body").css({ overflowY: 'hidden' });
            return false;
        });
        
        $(".dp-close").click(function(){
            var myelement = $(this).attr("href")
            $(myelement).animate({left:"-300px"}, 200);
            $.cookies.set('dp-panel', 'closed');
            $("body").css({ overflowY: 'auto' });
            return false;
        });
        
        $(".dp-toggle").click(function(){
            var myelement = $(this).attr("href")
            $(myelement).toggle();
            $(this).next('button.dp-button-submit').toggle();
            return false;
        });
        
        // For Input Labels
        $('input, textarea').focus(function () {
            $(this).prev('label').hide(200);
        })
        .blur(function () {
            $(this).prev('label').show(200);
        });
    });