var fs = require('fs');
require('dotenv').config({path:'../.env'});

var options = {
    key: fs.readFileSync(process.env.SSL_KEY),
    cert: fs.readFileSync(process.env.SSL_CERT)
};
var app = require('https').createServer(options);
var io = require('socket.io')(app);
var Redis = require('ioredis');
var redis = new Redis('redis://' + process.env.REDIS_HOST + ':' + process.env.REDIS_PORT);
var http = require('http');
var conns = {};
var host = process.env.APP_URL.replace('https://', "").replace('http://', "");

app.listen(8890, function () {
    console.log('Server is running!');
});

io.on('connection', function (socket) {
    console.log('connection');
    socket.auth = false;

    setTimeout(function () {
        if (!socket.auth) {
            console.log("Disconnecting socket ", socket.id);
            socket.disconnect('unauthorized');
        }
    }, 2000);

    socket.on('authenticate', function (reqData) {
        
        var options = {
            host: host,
            port: 80,
            method: 'GET',
            path: '/api/v1/users/verify_id/' + reqData.id,
            headers: {
                'Authorization': 'Bearer ' + reqData.token
            }
        };
        request = http.get(options, function (res) {
            var body = "";
            res.on('data', function (resp) {
                body += resp;
            });
            res.on('end', function () {
                console.log("Authenticated socket ", socket.id);
                socket.emit('private-App.User.' + reqData.id, 'done');
                socket.auth = true;
                conns[reqData.id] = socket;
            })
            res.on('error', function (e) {
                console.log("Got error: " + e.message);
                socket.disconnect('unauthorized');
            });
        });
    });
    socket.on('disconnect', function () {
        console.log("client disconnecting");
    });
});

redis.psubscribe('*', function (err, count) {
    console.log('psubscribe', err, count);
});
redis.on('pmessage', function (subscribed, channel, message) {
    message = JSON.parse(message);
    console.log('Channel is ', channel, ' and message is ', message, ' and user is ',message.data.for_user_id);
    if (conns[message.data.message.for_user_id]) {
        conns[message.data.message.for_user_id].emit(channel, message.data);        
    }
});