function getContainerMargin() { 
  try {
    var p = document.querySelector(".main-content > .container");
    if (!p) return;

    var style = p.currentStyle || window.getComputedStyle(p);
    
    var sidenav = document.querySelector('.sidenav');
    if (sidenav) {
      sidenav.style.right = style.marginRight;
      sidenav.style.display = 'block';
    }
  } catch (err) {
    console.warn('Error in getContainerMargin:', err.message);
  }
}

// Only attach event listeners if document is fully loaded
if (document.readyState === 'complete' || document.readyState === 'interactive') {
  window.addEventListener('load', getContainerMargin, false);
  window.addEventListener("resize", getContainerMargin);
} else {
  document.addEventListener('DOMContentLoaded', function() {
    window.addEventListener('load', getContainerMargin, false);
    window.addEventListener("resize", getContainerMargin);
  });
}