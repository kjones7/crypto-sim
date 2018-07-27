const jsdom = require("jsdom");
const { JSDOM } = jsdom;

JSDOM.fromURL("http://localhost/").then(dom => {
    console.log(dom.serialize());
});