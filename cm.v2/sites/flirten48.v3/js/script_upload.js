	var Flash;
	var uploadedfilescount = 0;

	//Init "flash" object
	function InitFlashObj()
	{
		if(document.embeds && document.embeds.length>=1)
			Flash = document.getElementById("EmbedFlashFilesUpload");
		else
			Flash = document.getElementById("FlashFilesUpload");
	}

	/*
		MultiPowUpload_onMovieLoad. Invoked when the movie loaded and if browsers supports ExternalInterface i.e 
		 programming interface that enables straightforward communication between Flash Movie and JavaScript.
	*/
	function MultiPowUpload_onMovieLoad()
	{
		InitFlashObj(); //sometimes Flash not initialized while window.onload event
		Flash.width = 1;
		Flash.height = 1;
	}
	
	/*
		flash.browseFiles(typelist) displays a file-browsing dialog box in which the user can select a local file to upload. 
		The dialog box is native to the user's operating system.
		typelist - An array of file types used to filter the files displayed in the dialog box.
	*/
	function browsefiles()
	{
    var allTypes = new Array();
		var imageTypes = new Object();
		imageTypes.description = "All files";
		imageTypes.extension = "*.*";
		allTypes.push(imageTypes);	
		imageTypes = new Object();
		imageTypes.description = "Images (*.JPG;*.JPEG;*.JPE;*.GIF;*.PNG;)";
		imageTypes.extension = "*.jpg; *.jpeg; *.jpe; *.gif; *.png;"; 
		allTypes.push(imageTypes);	
		Flash.removeAll();
		updateProgressBar(0);
		Flash.browseFiles(allTypes);
	}
	
	//Clear list
	function clearListBox(lstBox)
	{		
		while (lstBox.length > 0)
			lstBox.remove(0);
	}
	
	//Update HTML progress bar state
	function updateProgressBar(percentDone)
	{
		var rowProgress = document.getElementById("rowProgress");
		if(percentDone>=1)
			rowProgress.width = percentDone + "%";
		else
			rowProgress.width = "1";
	}
	
	/*
		flash.uploadAll(url) Starts the upload of a files in list to a remote server.
		url - The URL of the server script configured to handle upload through HTTP POST calls.
	*/
	function upload(sess_id)
	{
		if(document.getElementById("fileslist").options[0].text != '')
		{
			updateProgressBar(0);
			uploadedfilescount = 0;
			document.getElementById("lable").innerHTML = "";
			Flash.uploadAll('upload.php?sess_id=' + sess_id);
		}
		else
		{
			alert(upload_alert);
		}
	}
	function uploadcard()
	{ 
		updateProgressBar(0);
		uploadedfilescount = 0;
		document.getElementById("lable").innerHTML = "";
		Flash.uploadAll('uploadcard.php');
	}
	//MultiPowUpload_onSelect. Invoked when the user selects a file to upload or download from the file-browsing dialog box.
	function MultiPowUpload_onSelect()
	{
		var list = Flash.fileList();
		var fileslist = document.getElementById("fileslist");
		var i = 0;
		clearListBox(fileslist);
		for(i=0; i<list.length; i++)
		{ 
			fileslist.options[fileslist.options.length] = new Option(list[i].name +  " (" + list[i].size + " bytes)", i, false, false );
			document.getElementById("txtFiles").value += list[i].name+"|||";
		}
	}
	
	//MultiPowUpload_onProgress. Invoked periodically during the file upload or download operation
	function MultiPowUpload_onProgress(type, index, fileBytesLoaded, fileBytesLength, totalBytesLoaded, totalBytesLength) 
	{
	   var PercentDone = new Number((totalBytesLoaded/totalBytesLength)*100).toFixed(1);
	   document.getElementById("lable").innerHTML = PercentDone + "% uploaded" + " (" + uploadedfilescount + " files)";
	   updateProgressBar(PercentDone);
	}
	
	//MultiPowUpload_onError. Invoked when an input/output error occurs or when an upload/download fails because of an HTTP error
	function MultiPowUpload_onError(type, index, error) 
	{
	   window.alert(error);
	}
	
	//MultiPowUpload_onComplete. Invoked when the upload or download of single file operation has successfully completed
	function MultiPowUpload_onComplete(type, index)
	{
		uploadedfilescount++;
	}
	
	//MultiPowUpload_onCompleteAbsolute. Invoked when the upload or download of all files operation has successfully completed
	function MultiPowUpload_onCompleteAbsolute(type, totalBytesLoaded)
	{
	  	document.getElementById("lable").innerHTML = "Upload complete! Total bytes " + totalBytesLoaded + " (" + uploadedfilescount + " files)";
		var fileslist = document.getElementById("fileslist");
		self.location.reload(true);
		clearListBox(fileslist);
	}
