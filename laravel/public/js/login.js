async function login() {

    const name = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const message = document.getElementById("message");

    if(name === "" || password === ""){
        message.textContent = "ユーザー名とパスワードを入力してください";
        return;
    }

    try {

        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                password: password
            })
        });

        const result = await response.json();

        if(!response.ok){
            throw new Error(result.message);
        }

        message.textContent = result.message;

        window.location.href = '/home';

        console.log(result);

    } catch(error) {

        message.textContent = error.message;

    }
}