

<script>
// self executing function here
!function(){function e(e){var t=window.scrollY||window.pageYOffset,n=t+window.innerHeight,i=e.getBoundingClientRect().top+t;return i+e.offsetHeight>=t-100&&i<=n+100}var t=function(e){if(!e.classList.contains("loaded")){var t=e.src.replace("/placeholder",""),n=e.clientWidth;t+="?d="+50*Math.ceil(n/50),e.src=t,e.classList.add("loaded")}};document.body.addEventListener&&(document.body.addEventListener("load",function(n){var i;(i=n.target).classList&&i.classList.contains("slimage")&&e(i)&&t(i)},!0),window.addEventListener("scroll",function(n){document.querySelectorAll(".slimage").forEach(function(n){e(n)&&t(n)})},!0)),function(n){for(var i=document.getElementsByClassName("slimage"),o=0;o<i.length;o++)i[o].complete&&e(i[o])&&t(i[o])}()}();
</script>

</body>
</html>
