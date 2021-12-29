function b9() {
    document.getElementById("inp_viv").value += 9;
}
function b8() {
    document.getElementById("inp_viv").value += 8;
}
function b7() {
    document.getElementById("inp_viv").value += 7;
}
function b6() {
document.getElementById("inp_viv").value += 6;
    }
function b5() {
    document.getElementById("inp_viv").value += 5;
}
function zap() {
    document.getElementById("inp_viv").value += ".";
}
function prc() {
    document.getElementById("inp_viv").value += " % ";
}
function b4() {
    document.getElementById("inp_viv").value += 4;
}
function b3() {
    document.getElementById("inp_viv").value += 3;
}
function b2() {
    document.getElementById("inp_viv").value += 2;
}
function b1() {
    document.getElementById("inp_viv").value += 1;
}
function b0() {
    document.getElementById("inp_viv").value += 0;
    if (document.getElementById("inp_viv").value.indexOf(' / 0') !== -1) {
        alert("На ноль делить нельзя");
    }
}
function del() {
    document.getElementById("inp_viv").value += " / ";
}
function min() {
    document.getElementById("inp_viv").value += " - ";
}
    
function plus() {
    document.getElementById("inp_viv").value += " + ";
}
function umn() {
    document.getElementById("inp_viv").value += " * ";
}
setInterval (function rav() {
    a = document.getElementById("inp_viv").value;
    if (a !== "") {
        document.getElementById("viv_sp").innerHTML = eval(a);
    }
}, 100);
function stp() {
    document.getElementById("inp_viv").value = "";
    document.getElementById("viv_sp").innerHTML = "";
}
function kor() {
    document.getElementById("inp_viv").value += " ** 0.5 ";
}
function st() {
    a = document.getElementById("inp_viv").value;
    b = a.slice(0, -1)
    a = document.getElementById("inp_viv").value = b;
}