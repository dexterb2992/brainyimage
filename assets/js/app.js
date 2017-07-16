(function (brainyImage){
	brainyImage(window, document, window.jQuery);
}(function brainyImage(window, document, $){
	$(function (){
		var img_zone = document.getElementById('img-zone'),	
			collect = {
				filereader: typeof FileReader != 'undefined',
				zone: 'draggable' in document.createElement('span'),
				formdata: !!window.FormData
			}, 
			acceptedTypes = {
				'image/png': true,
				'image/jpeg': true,
				'image/jpg': true
			};
		
		// Call AJAX upload function on drag and drop event
		function dragHandle(element) {
			element.ondragover = function () { return false; };
			element.ondragend = function () { return false; };
			element.ondrop = function (e) { 
				e.preventDefault();
				$("#results").removeClass("hidden");
				var files = e.dataTransfer.files;
				for (var i = files.length - 1; i >= 0; i--) {
				
			       	$entry = generateHTMLEntries(files, i);

					singleUpload(files[i], $entry);
					$entry = "";
				}
			}  		
		}
		
		if (collect.zone) {  		
			if( img_zone != null ){
				dragHandle(img_zone);
			}
		} 
		else {
			alert("Drag & Drop isn't supported, use Open File Browser to upload photos.");			
		}

		// Call AJAX upload function on image selection using file browser button
		$(document).on('change', '#file_handle', function() {
			$("#results").removeClass("hidden");
			var files = this.files;

			for (var i = files.length - 1; i >= 0; i--) {
				
		       	$entry = generateHTMLEntries(files, i);

				singleUpload(files[i], $entry);
				$entry = "";
			}		
		});

		$(document).on("click", '.btn-retry', function (){
			var $this = $(this),
				$el = $this.parents(".entry:first"),
				oldSize = $el.find('.size-after').text(),
				files = [{
					'name': $this.attr("data-url"),
					'oldSize': oldSize
				}],
				$entry = generateHTMLEntries(files, 0);

			$el.append($entry);

			var data = {
				url: $this.attr("data-url"),
				filename: '**from_url**'
			};

			compressImage(data, $entry);
		});

		$(document).on("click", ".view-image-diff", function (){
			var $this = $(this), $modal = $("#image_difference_modal");

			var $originalDiv = $modal.find(".image-original");
			var $optimizedDiv = $modal.find(".image-optimized");

			var $entry = $this.parents(".entry:first");

			var $downloadLink = $entry.find(".download-link");

			$originalDiv.find("img").attr("src", $downloadLink.attr("data-orig-image"));
			$optimizedDiv.find("img").attr("src", $downloadLink.attr("href"));

			$originalDiv.find(".size").html( $entry.find(".size-before").parent("div").html() );

			var $newSizeAfter = $entry.find(".size-after").parent("div").clone();
			$newSizeAfter.find(".view-image-diff").remove();
			$optimizedDiv.find(".size").html( $newSizeAfter.html() );


			$modal.modal();
		});

		$(".btn-file").click(function (){
			$("#file_handle").click();
		});
		
		// File upload progress event listener
		(function($, window, undefined) {
			var hasOnProgress = ("onprogress" in $.ajaxSettings.xhr());
		
			if (!hasOnProgress) {
				return;
			}
			
			var oldXHR = $.ajaxSettings.xhr;
			$.ajaxSettings.xhr = function() {
				var xhr = oldXHR();
				if(xhr instanceof window.XMLHttpRequest) {
					xhr.addEventListener('progress', this.progress, false);
				}
				
				if(xhr.upload) {
					xhr.upload.addEventListener('progress', this.progress, false);
				}
				
				return xhr;
			};
		})(jQuery, window);	
	});

	// Function to show messages
	function ajax_msg(status, msg) {
		var the_msg = '<div class="alert alert-'+ (status ? 'success' : 'danger') +'">';
		the_msg += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		the_msg += msg;
		the_msg += '</div>';
		$(the_msg).insertBefore($("#img-zone"));
	}

	// rest of the codes here
	function number_format (number, decimals, decPoint, thousandsSep) { // eslint-disable-line camelcase
	  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	  var n = !isFinite(+number) ? 0 : +number;
	  var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
	  var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep;
	  var dec = (typeof decPoint === 'undefined') ? '.' : decPoint;
	  var s = '';
	  var toFixedFix = function (n, prec) {
	    var k = Math.pow(10, prec);
	    return '' + (Math.round(n * k) / k)
	      .toFixed(prec);
	  }
	  // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
	  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	  if (s[0].length > 3) {
	    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	  }
	  if ((s[1] || '').length < prec) {
	    s[1] = s[1] || '';
	    s[1] += new Array(prec - s[1].length + 1).join('0');
	  }
	  return s.join(dec);
	}

	function formatSizeUnits(bytes){
		var ext = 'byte';
	    if (bytes >= 1073741824){
	        bytes = number_format((bytes/1073741824), 2);
	        ext = 'GB';
	    }else if (bytes >= 1048576){
	        bytes = number_format((bytes/1048576), 2);
	        ext = 'MB';
	    }else if (bytes >= 1024){
	        bytes = number_format((bytes/1024), 2);
	        ext = 'KB';
	    }else if (bytes > 1){
	        ext = 'bytes';
	    }else if (bytes == 1){
	        ext = 'byte';
	    }else{
	        bytes = '0';
	    }

	    return bytes+' '+ext;
	}

	function singleUpload(file, $el){
		var progressbar = $el.find(".progress-bar");
		var formData = new FormData();
		formData.append('q', 'upload');
		formData.append('img_file[]', file);

		$("#results").append($el);

		(function uploading(){
			$.ajax({
				url : "process.php", // Change name according to your php script to handle uploading on server
				type : 'post',
				data : formData,
				assync: false,
				dataType : 'json',						
				processData: false,
				contentType: false,
				progress: function(evt) {
					if(evt.lengthComputable) {
						var pct = (evt.loaded / evt.total) * 100;
						console.log("Upload progres.."+pct+"%");

						progressbar.css({"width": pct+"%"}).html(number_format(pct)+'%');							
					}
					else {
						console.warn('1. Content Length not reported!');
					}
				},
				success: function(data){
					if( data.success ){

						progressbar.css({"width": '100%'}).html("Compressingg")
							.removeClass("bg-info").addClass("bg-success");

						compressImage(data, $el);
					}else{
						ajax_msg(false, 'An error has occured while uploading photo.'); 
					}
				},
				error : function(data){
					console.warn(data);
					ajax_msg(false, 'An error has occured while uploading photo.'); 								
				}
			});	
		})();
	}

	function compressImage(data, $el){
		var progressbar = $el.find(".progress-bar");
		(function compressing(i){
			$.ajax({
				url: 'process.php',
				type: 'post',
				assync: false,
				data: {
					q: 'compress',
					src: data.url,
					filename: data.filename
				},
				dataType: 'json',
				progress: function (evt){
					if(evt.lengthComputable) {
						var pct = (evt.loaded / evt.total) * 100;
						progressbar.css({"width": pct+"%"}).html('Compressing');							
					}else {
						console.warn('2. Content Length not reported!');
					}
				},
				success: function (response){
					progressbar.css({"width": '100%'}).html("Finished")
						.removeClass("progress-bar-animated")
						.removeClass("progress-bar-striped");

					if( response.success ){
						$el.find('.size-after').html(response.output.size);
						$el.find('.size-diff').html("-"+response.output.diff);
						$el.find(".download-link").attr("href", response.output.url)
							.removeClass("disabled")
							.html('<i class="fa fa-cloud-download"></i> Download')
							.attr("data-orig-image", response.input.url);
						$el.find('.btn-retry').attr("data-url", response.output.url)
							.removeClass("disabled")
							.html('<i class="fa fa-refresh"></i> Retry ');
					}else{
						if( response.hasOwnProperty('error') && response.hasOwnProperty('error_description') ){
							$el.find('.size-after').html(response.error_description)
								.removeClass('badge-primary').addClass("badge-danger");
							$el.find(".download-link").remove();
							ajax_msg(false, response.error);
						}else{
							ajax_msg(false, 'An error has occured while compressing your image.');
						}
					}
					
				},
				error: function (){
					ajax_msg(false, 'An error has occured while requesting to compress an image.');
				}
			});
		})();
	}

	function generateHTMLEntries(files, i){
		var $entry = $("<div class='entry' id='entry_"+files[i]['name']+"'></div>");
       	var $row = $('<div class="row"></div>');
       	var $file_before = $('<div class="col-md-3 file-before"></div>');
       	var $file_progress = $('<div class="col-md-6 row file-progress">'+
       		'<div class="col-md-3 col-sm-3">'+
            '</div>'+
            '<div class="col-md-6 col-sm-6">'+
            '</div>'+
            '<div class="col-md-3 col-sm-3">'+
            '</div>'+
       		+'</div>');
       	var $file_after = $('<div class="col-md-3 file-after"></div>');

       	var sizeBefore = formatSizeUnits(files[i]['size']);

       	if( files[i].hasOwnProperty('oldSize') ){
       		sizeBefore = files[i]['oldSize'];
       	}


       	var $span_size_before = $('<span class="size-before badge badge-info">'+sizeBefore+'</span>');
        var $span_size_after = $('<span class="size-after badge badge-primary"></span>');
        var $span_size_diff = $('<span class="size-diff badge badge-danger"></span>');
        var $span_progress = $('<span class="progress">'+
            '<span id="progress_'+files[i]['name']+'" class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" aria-valuenow="0%" style="width: 0%;height: 19px;" aria-valuemin="0" aria-valuemax="100"></span>'+
        '</span>');
        var $download_link = $('<a class="disabled download-link btn btn-sm btn-warning" href="javascript:void(0);" download><a>'+
        		'<a title="Optimize again" class="disabled btn-retry btn btn-sm btn-success" href="javascript:void(0);"></a>');

        var $view_diff = $('<a href="javascript:void(0)" title="View image difference" class="label text-white view-image-diff pull-right"><i class="fa fa-eye"></i></a>');

        $file_before.append( $('<span title="'+files[i]['name']+'">'+files[i]['name']+'</span>') );

       	$file_progress.children('div:first').append( $span_size_before );
       	$file_progress.children('div:nth-child(2)').append($span_progress);
       	$file_progress.children('div:last').append($span_size_after);
       	$file_progress.children('div:last').append($view_diff);

       	$file_after.append($download_link);
       	$file_after.append($span_size_diff);

       	$row.append( $file_before );
       	$row.append( $file_progress );
       	$row.append( $file_after );

       	$entry.append( $row );

       	return $entry;
	}
}));