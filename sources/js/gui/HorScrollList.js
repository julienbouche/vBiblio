function getPos(el) {
    // yay readability
    var lx=0, ly=0;
    
    while (el != null){
	lx += el.offsetLeft;
	ly += el.offsetTop;
	el = el.offsetParent;
    }
    
    return {x: lx,y: ly};
}

function initNewList(dom_element) {
    var galW   = dom_element.offsetWidth,
            galSW  = dom_element.scrollWidth,
            wDiff  = (galSW/galW)-1,  // widths difference ratio
            mPadd  = 100,  // Mousemove Padding
            damp   = 30,  // Mousemove response softness
            mX     = 0,   // Real mouse position
            mX2    = 0,   // Modified mouse position
            elementXOffset = 0, //element positioning in document ref
            posX   = 0,
            mmAA   = galW-(mPadd*2), // The mousemove available area
            mmAAr  = (galW/mmAA);    // get available mousemove fidderence ratio
            
    elementXOffset = getPos(dom_element).x;
            
    dom_element.onmousemove=function(e) {
            
            mX = e.pageX - elementXOffset ;
            mX2 = Math.min( Math.max(0, mX-mPadd), mmAA ) * mmAAr;
    };

    setInterval(function(){
            posX += (mX2 - posX) / damp; // zeno's paradox equation "catching delay"	
            dom_element.scrollLeft = (posX*wDiff);
    }, 20);
}

window.onload = (function(){
    scrollable_elements = document.querySelectorAll(".HorScrollList");
    
    for(i=0; i<scrollable_elements.length; i++) {
        initNewList(scrollable_elements[i]);   
    }
});