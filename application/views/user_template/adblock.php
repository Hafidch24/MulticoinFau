<script>
var cp = document.getElementById('copyright');
    if(cp == null){
        window.location = "https://www.wiour.com";
    }else{
        var TestAd="//static.surfe.pro/js/net.js";function init(){adsBlocked(function(a){a&&(Swal.fire("Disable your Ad Blocker before using our service."),setTimeout(function(){window.location.href=""},5e3))})}function adsBlocked(a){fetch(new Request("//adoto.net/dashboard/display/items.php",{method:"HEAD",mode:"no-cors"})).then(function(a){return a}).then(function(b){console.log(b),a(!1)}).catch(function(b){console.log(b),a(!0)})}!function(){var b=performance.now(),a=document.createElement("script");a.onload=function(){(performance.now()-b).toFixed(2),a.parentNode.removeChild(a)},a.onerror=function(){var a=(performance.now()-b).toFixed(2)+"ms";Swal.fire("Ad Blocker Detected","script failed to load after "+a+", Disable it or you will see this forever.","error"),setTimeout(function(){window.location.href=""},5e3)},a.src=TestAd,document.body.appendChild(a)}(),document.addEventListener("DOMContentLoaded",init,!1)
        cp.innerHTML = ' | Powered by <a href="https://ourtecads.com" target="_blank">Ourtecads</a>.';
    }
</script>