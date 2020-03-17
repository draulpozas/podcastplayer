window.onload = init;

var background;
var subscriptions;
var episodes;
var playlist;
var aud;
var updateInterval;

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
    }

    let bin = document.getElementById('bin');
    bin.ondragover = function(ev) {
        ev.preventDefault();
    }
    bin.ondrop = function(ev) {
        playlist.getElementsByClassName('draggin')[0].remove();
    }
    loadSubscriptions();
}

function trackDiv(episode) {
    let div = document.createElement('div');
    div.classList.add('track');
    div.innerHTML = '▶ '+ episode.title;
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
        return false;
    } else if (aud.paused) {
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
    subscriptions.forEach(sub => {
        let div = document.createElement('div');
        div.classList.add('podcast');
        div.onclick = function() {
            loadEpisodes(sub.id);
        }
        div.innerHTML = sub.name;
        background.appendChild(div);
    });
}

function getSubscriptions() {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // console.log(this.responseText);
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