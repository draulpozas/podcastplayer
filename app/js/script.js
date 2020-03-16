window.onload = loadSubscriptions;
var background;
var subscriptions;
var episodes;
function loadSubscriptions() {
    getSubscriptions();
    background = document.getElementById('background');
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
            subscriptions = JSON.parse(this.responseText);
            console.log(subscriptions);
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
        div.onclick = function() {
            playNow(episode);
        }
        div.innerHTML = episode.title;
        background.appendChild(div);
    });
}

function getEpisodes(subId) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // console.log(JSON.parse(this.responseText));
            episodes = JSON.parse(this.responseText);
        }
    }
    xhttp.open('GET', 'php/getEpisodes.php?subscription='+subId, false);
    xhttp.send();
}

function playNow(episode) {
    let title = episode.title;
    let src = episode.src;
    console.log(`now playing: ${title} ( ${src} )`);
    
}