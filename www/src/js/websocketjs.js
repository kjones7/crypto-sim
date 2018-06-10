var conn = new WebSocket('ws://127.0.0.1:8079');
conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onmessage = function(e) {
    console.log(e.data);
};