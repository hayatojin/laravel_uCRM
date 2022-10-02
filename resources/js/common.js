const nl2br = (str) => {
    var res = str.replace(/\r\n/g, "<br>");
    res = res.replace(/(\n|\r)/g, "<br>");
    return res;
  }

// exportとすることで、関数を外でも使えるようにする
export { nl2br }