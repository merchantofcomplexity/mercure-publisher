let ul = null;

const es = new EventSource('http://localhost:3000/hub?topic=events');

es.onmessage = e => {

    const data = JSON.parse(e.data);

    if (!ul) {
        ul = document.createElement('ul');

        const messages = document.getElementById('main--container');

        messages.innerHTML = '';

        messages.append(ul);
    }

    const li = document.createElement('li');

    li.append(document.createTextNode(data.headline));

    ul.append(li);
};
