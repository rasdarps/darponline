<img
    srcset="{{ $media->getSrcset($collection) }}"
    src="{{ $media->getUrl($collection) }}"
    sizes="1px"
    width="{{ $media->getCustomProperty('width') }}"
    height="{{ $media->getCustomProperty('height') }}"
    alt="{{ $alt ?: $media->name }}"
    loading="lazy"
    class="{{ $class }}"
    onload="window.requestAnimationFrame(function(){if(!(size=getBoundingClientRect().width))return;onload=null;sizes=Math.ceil(size/window.innerWidth*100)+'vw';});"
/>