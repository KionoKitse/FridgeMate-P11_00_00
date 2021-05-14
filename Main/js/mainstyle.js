//Set the center element width based on window size
function SetCenterWidth(){
    var w = window.innerWidth;
    var elements = document.getElementsByClassName("center");
    if (w>400){
        elements[0].style.width=("400px");
    }
}