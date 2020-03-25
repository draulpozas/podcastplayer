window.onload = init;

var background;
var subscriptions;
var episodes;
var playlist;
var aud;
var updateInterval;
var playqueue = [];

function init() {
    background = document.getElementById('background');
    playlist = document.getElementById('playlist');
    playlist.ondragover = function(ev) {
        ev.preventDefault();
    }
    playlist.ondrop = function(ev) {
        let episode = JSON.parse(ev.dataTransfer.getData('episode'));
        let div = trackDiv(episode);
        adjustSpacer();
        playlist.getElementsByClassName('container')[0].appendChild(div);
        updateQueue();
    }

    let bin = document.getElementById('bin');
    bin.ondragover = function(ev) {
        ev.preventDefault();
    }
    bin.ondrop = function(ev) {
        playlist.getElementsByClassName('draggin')[0].remove();
        updateQueue();
    }
    loadSubscriptions();
}

function trackDiv(episode) {
    let div = document.createElement('div');
    div.classList.add('track');
    div.dataset.jsonstring = JSON.stringify(episode);
    div.innerHTML = '▶ '+ episode.title;

        let span = document.createElement('span');
        span.innerHTML = episode.podcast;
        span.classList.add('subtitle');
        div.appendChild(span);
    
    div.onclick = function() {
        if (aud) {
            aud.pause();
        }
        console.log(episode);
        aud = loadAudio(episode.src);
        document.getElementById('title').innerHTML = episode.title;
        aud.currentTime = 0;
        playpause();
    }

    div.draggable = 'true';
    div.ondragstart = function(ev) {
        div.classList.add('draggin');
        document.getElementById('bin').style.left = '0';
    }
    div.ondragend = function(ev) {
        document.getElementById('bin').style.left = '-100%';
        div.classList.remove('draggin');
    }

    return div;
}

function loadAudio(src) {
    return new Audio(src);
}

function forwards() {
    aud.currentTime += 30;
    updateProgress();
}

function backwards() {
    aud.currentTime -= 30;
    updateProgress();
}

function playpause() {
    if (!aud) {
        if (playqueue.length > 0) {
            aud = loadAudio(playqueue[0].src);
            document.getElementById('title').innerHTML = playqueue[0].title;
        } else {
            return false;
        }
    }
    if (aud.paused) {
        aud.play();
        document.getElementById('playBtn').innerHTML = '||';
        updateInterval = setInterval(updateProgress, 1000);
    } else {
        aud.pause();
        document.getElementById('playBtn').innerHTML = '▶';
        clearInterval(updateInterval);
    }
}

function updateProgress() {
    if (aud.ended) {
        clearInterval(updateInterval);
        document.getElementById('playBtn').innerHTML = '▶';
    } else {
        let perc = aud.currentTime / aud.duration * 100;
        document.getElementById('progress').style.width = perc+'%';
        updateTimer();
    }
}

function updateTimer() {
    let timer = document.getElementById('timer');

    let mins = Math.floor(aud.currentTime/60);
    let secs = Math.floor(aud.currentTime - (mins * 60));
    let current = mins +':'+ (''+secs).padStart(2, '0');

    mins = Math.floor(aud.duration/60);
    secs = Math.floor(aud.duration - (mins * 60));
    let total = mins +':'+ (''+secs).padStart(2, '0');

    timer.innerHTML = current +' / '+ total;
}

function adjustSpacer() {
    let tracks = document.getElementsByClassName('track').length+1;
    let margin = tracks*2.5;
    margin = margin > 16 ? 16 : margin;
    document.getElementById('spacer').style.height = margin+'em';
}

function loadSubscriptions() {
    getSubscriptions();
    background.innerHTML = '';
    adder();
    subscriptions.forEach(sub => {
        let div = document.createElement('div');
        div.classList.add('podcast');
        div.onclick = function() {
            loadEpisodes(sub.id);
        }
        div.innerHTML = sub.name;

            // let podLogo = document.createElement('img');
            // podLogo.classList.add('podLogo');
            // podLogo.src = sub.img;
            // div.appendChild(podLogo);

        background.appendChild(div);
    });
}

function getSubscriptions() {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            subscriptions = JSON.parse(this.responseText);
        }
    }
    xhttp.open('GET', 'php/getSubscriptions.php', false);
    xhttp.send();
}

function loadEpisodes(subId) {
    background.innerHTML = '';
    getEpisodes(subId);
    episodes.forEach(episode => {
        let div = document.createElement('div');
        div.classList.add('episode');
        div.classList.add('podcast');
        div.innerHTML = episode.title;
        div.draggable = 'true';
        div.ondragstart = function(ev) {
            ev.dataTransfer.setData('episode', JSON.stringify(episode));
        }
        background.appendChild(div);
    });
}

function getEpisodes(subId) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // console.log(this.responseText);
            episodes = JSON.parse(this.responseText);
        }
    }
    xhttp.open('GET', 'php/getEpisodes.php?subscription='+subId, false);
    xhttp.send();
}

function hidePlaylist() {
    playlist.classList.toggle('hidden');
}

function updateQueue() {
    let items = document.querySelectorAll('.track');
    playqueue = [];
    
    items.forEach(item => {
        playqueue.push(JSON.parse(item.dataset.jsonstring));
    });
}

function adder() {
    let div = document.createElement('div');
    let input = document.createElement('input');
    let btn = document.createElement('button');
    div.appendChild(input);
    div.appendChild(btn);

    btn.innerHTML = '+';
    input.type = 'text';
    input.id = 'subscribeLink';
    div.classList.add('podcast');
    div.classList.add('adder');

    btn.onclick = subscribe;

    background.appendChild(div);
}

function subscribe() {
    let link = document.getElementById('subscribeLink').value;
    document.getElementById('subscribeLink').value = '';

    link = link.trim();
    if (link.length < 3) {
        window.alert('Please enter a valid RSS feed.');
        return false;
    }

    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.status == 200 && this.readyState == 4) {
            console.log(this.responseText);
            if (this.responseText == '1') {
                loadSubscriptions();
            } else {
                window.alert('Could not subscribe: invalid RSS');
            }
        }
    }

    xhttp.open('GET', 'php/subscribe.php?link='+link, true);
    xhttp.send();
}

function changeVolume() {
    let value = document.getElementById('volumeslider').value;
    aud.volume = value/17;
}




window.onkeyup = function(ev) {
    if (ev.key == ' ') {
        ev.preventDefault();
        playpause();
    } else if (ev.key == 'ArrowRight') {
        ev.preventDefault();
        forwards();
    } else if (ev.key == 'ArrowLeft') {
        ev.preventDefault();
        backwards();
    }
}