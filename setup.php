<?php include "includes/header.php"?>


  <?php include "includes/nav.php"?>
  
<div class="setup-wrapper">
<div class="setup-step">
    <div class="setup-title"><h2>Copy the code below</h2>
    <button class="btn btn-primary copy-code">Click to copy</button></div>
    <input readonly class="setup-js code-block" value='
    !function(){function e(e){var t=window.scrollY||window.pageYOffset,n=t+window.innerHeight,i=e.getBoundingClientRect().top+t;return i+e.offsetHeight>=t-100&&i<=n+100}var t=function(e){if(!e.classList.contains("loaded")){var t=e.src.replace("/placeholder",""),n=e.clientWidth;t+="?d="+50*Math.ceil(n/50),e.src=t,e.classList.add("loaded")}};document.body.addEventListener&&(document.body.addEventListener("load",function(n){var i;(i=n.target).classList&&i.classList.contains("slimage")&&e(i)&&t(i)},!0),window.addEventListener("scroll",function(n){document.querySelectorAll(".slimage").forEach(function(n){e(n)&&t(n)})},!0)),function(n){for(var i=document.getElementsByClassName("slimage"),o=0;o<i.length;o++)i[o].complete&&e(i[o])&&t(i[o])}()}();'/>
</div>
<div class="setup-step">
<div class="setup-title">
    <h2>Include the script on your webpages.</h2>
    <p>You must include the script on every webpage you intend to use Slimage images. We reccomend placing the code inside the bottom of the body.</p>
</div>
<pre class="code-block"><code>&lt;html&gt;
    &lt;head&gt;&lt;/head&gt;
    &lt;body&gt;
        ...
        <span class="text-primary-color">Code snippet goes here.</span>
    &lt;/body&gt;
&lt;/html&gt;</code></pre>
</div>
<div class="setup-step">
    <div class="setup-title"><h2>Setup Images</h2>
    <p>Copy the image source from your dashboard and put it in an image tag, then add the "slimage" class. Repeat for all of your Slimage images.</p>
</div>
<pre class="code-block"><code>&lt;img class="slimage" href="d2yp72sq5kkz8x.cloudfront.net/apikey/placeholder/test.png"&gt;</code></pre>
</div>
<div class="setup-step">
    <div class="setup-title">
    <p class="alert alert-info"><i class="material-icons">info</i> If you want to load an image regularly (original size and without lazy loading), simply remove the "slimage" class and the "placeholder" path from the URL.</p>
</div>
<pre class="code-block"><code>&lt;img <span class="text-primary-color">class="slimage"</span> href="d2yp72sq5kkz8x.cloudfront.net/apikey/<span class="text-primary-color">placeholder</span>/test.png"&gt;</code></pre>

<div class="setup-step">
    <div class="setup-title"><h2>Thats it!</h2></div>
</div>
</div>
  <?php include "includes/footer.php"?>
