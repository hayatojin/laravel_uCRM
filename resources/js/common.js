const nl2br = (str) => {
    var res = str.replace(/\r\n/g, "<br>");
    res = res.replace(/(\n|\r)/g, "<br>");
    return res;
  }

// 日付取得
const getToday = () => {
  const today = new Date();
 const yyyy = today.getFullYear();
 const mm = ("0"+(today.getMonth()+1)).slice(-2);
 const dd = ("0"+today.getDate()).slice(-2);
 return yyyy+'-'+mm+'-'+dd;
}

// exportとすることで、関数を外でも使えるようにする
export { nl2br }

export { getToday }