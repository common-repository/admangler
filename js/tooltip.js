function pw() { return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth; } 

function mouseX(evt) { return evt.clientX ? evt.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft) : evt.pageX; } 

function mouseY(evt) { return evt.clientY ? evt.clientY + (document.documentElement.scrollTop || document.body.scrollTop) : evt.pageY; } 

function popUp(evt,oi) 
{
    if (document.getElementById) 
    {
        var wp = pw(); 
        dm = document.getElementById(oi); 
        ds = dm.style; 
        st = ds.visibility; 
        if (dm.offsetWidth) 
            ew = dm.offsetWidth; 
        else if (dm.clip.width) 
            ew = dm.clip.width; 

        if (st == "visible" || st == "show") 
        {
            ds.visibility = "hidden"; 
            ds.left = "-9000px";
        }   
        else 
        {
            tv = mouseY(evt) + 20; lv = mouseX(evt) - (ew/4); 
            if (lv < 2) 
                lv = 2; 
            else if (lv + ew > wp) 
                lv -= ew/2; 
            lv += 'px';
            tv += 'px';  
            ds.left = lv; 
            ds.top = tv; 
            ds.visibility = "visible";
        }
    }
}
                  