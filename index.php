<?php include "includes/header.php"?>
<?php include "includes/nav.php"?>
<div class="landing-wrapper">
<div class="hero">
  <div class="hero-bg"></div>
  <div class="header">
    <img src="includes/logo-png.png" width="150"/>
  </div>
  <div class="example-images">
    <div class="example-img-wrapper">
      <h4>Original</h4>
      <img class="example-img" src="https://d2yp72sq5kkz8x.cloudfront.net/e5cd465b4734ee791dc05dfbfeb96d4d/art-bicycle-bike-623919.jpg" />
      <ul>
        <li>
          <span>Size:</span> 758 kb</li>
        <li>
          <span>Dimensions:</span> 4118 x 2715 px</li>
      </ul>
    </div>
    <div class="example-img-wrapper">
      <h4>Slimage</h4>

      <img class="slimage example-img" src="https://d2yp72sq5kkz8x.cloudfront.net/e5cd465b4734ee791dc05dfbfeb96d4d/placeholder/art-bicycle-bike-623919.jpg"
      />
      <ul>
        <li>
          <span>Size:</span> 57 kb</li>
        <li>
          <span>Dimensions:</span> 300 x 200 px</li>
      </ul>
    </div>
  </div>
  <div class="feature-overview">
    <div class="feature">
      <img src="icons/img-resizer.svg" />
      <p>Scaled to fit</p>
    </div>
    <div class="feature">
      <img src="icons/lazyload.svg" />
      <p>Lazy Loaded</p>
    </div>
    <div class="feature">
      <img src="icons/compress.svg" />
      <p>Compressed</p>
    </div>
    <div class="feature">
      <img src="icons/cdn.svg" />
      <p>Worldwide CDN</p>
    </div>
    <div class="feature">
      <img src="icons/setup.svg" />
      <p>Simple Setup</p>
    </div>

  </div>
</div>

<div class="feature-panel lazy-load">
  <h2>Scaled to fit</h2>
  <p>Stop loading 1000px images on 320px mobile device screens, these wasted pixels equate to wasted bandwith, which equates to slower page loads. Slimage intelligently loads your images at the exact pixel size they need to be displayed at.
</div>
<div class="feature-panel lazy-load">
  <h2>Lazy loaded</h2>
<p>Why waste time loading images that users are never going to see? Slimage only loads images when they are going to be viewed using lazy loading techniques. </p>
</div>
<div class="feature-panel lazy-load">
  <h2>Compressed</h2>
  <p>Image compression is an easy win. Slimage strips the metadata and compresses the image without losing quality to make for a smaller file size and a quicker load time.
</div>
<div class="feature-panel lazy-load">
  <h2>Worldwide distribution</h2>
  <p>No more slow server responses. Slimage is built on top of Cloudfront CDN and Lambda@Edge so your images will be available to your users wherever they need it in a fraction of the time.
</div>
<div class="feature-panel lazy-load">
  <h2>Simple setup</h2>
  <p>Upload your images. Link to the images in your code. Add a Slimage class. It's that simple to get up and running with Slimage image optimization.
</div>
<div class="feature-panel">
  
<a href="/" type="button" class="btn btn-primary btn-lg get-started">Get Started</a>

</div>
</div>
<?php include "includes/footer.php"?>