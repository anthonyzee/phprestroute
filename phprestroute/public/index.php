<!-- View stored in index.php -->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Home | RestClass</title>
		<meta name="description" content="">
		<meta name="author" content="">
		<script>
			function restApi() {
				var self=this;
				var reqObj=function(pUrl, pData, pCb){
						var xmlhttp=new XMLHttpRequest();
						xmlhttp.onreadystatechange=function(){
								if (xmlhttp.readyState==4){
										if (xmlhttp.status==200){
												var data=xmlhttp.responseText;
												if (data.charAt(0)=="{" || data.charAt(0)=="["){
													var jsonData=JSON.parse(xmlhttp.responseText);
													pCb(jsonData);	
												}else{
													pCb();
												}
												
										}else{
												pCb();
										}
								}
						}
						this.get=function(){
								xmlhttp.open("GET", pUrl, true);
								xmlhttp.send();
						}
						this.post=function(){
								xmlhttp.open("POST", pUrl, true);
								xmlhttp.setRequestHeader("Content-type","application/json");
								xmlhttp.send(pData);
						}
						
				}
				self.get=function(pUrl, pData, pCb){
					var req=new reqObj(pUrl, pData, pCb);
					req.get();
				}
				self.post=function(pUrl, pData, pCb){
					var req=new reqObj(pUrl, pData, pCb);
					req.post();
				}
			}
			var restapi=new restApi();
			restapi.get("api/dev", "", function(r){
				document.getElementById("version").innerText=JSON.stringify(r);
			});
		</script>
	</head>
	<body>
		<span id="version">-</span>
	</body>
</html>
