<template>
    <div class="one-armed-bandit">
        <h1 class="mb-5">Однорукий бандит</h1>
        <section class="machine">
            <div class="cover">
                <div class="inside-cover">
                    <div class="screen-1" data-option="fruit"></div>
                    <div class="screen-2" data-option="fruit"></div>
                    <div class="screen-3" data-option="fruit"></div>
                </div>
            </div>
        </section>
        <section class="panel">

            <input type="number" placeholder="$$$" id="bid"><button class="spin" id="spin">КРУТИТЬ</button>

        </section>
        <section class="summary py-3">
            <h3 class="mb-3 text-white">Доступно CashBack: <span data-summary="user-cash">0</span> $</h3>
            <p class="mb-0 text-white">Прошлый результат: <span data-summary="last-game"></span> $</p>
            <p class="mb-0 text-white">За сегодня попыток: <span data-summary="spins">0</span> раз</p>
            <p class="mb-0 text-white">Победы: <span data-summary="wins">0</span></p>
            <p class="mb-0 text-white">Проигрыши: <span data-summary="losses">0</span></p>
        </section>

    </div>

</template>
<script>

export default {
    data(){
       return {
           money_amount:1000,
       }
    },
    mounted(){
        class Wallet {
            constructor(money) {
                //private variable to store information
                let _money = money;
                //method below allow to download wallet value
                this.getWalletValue = () => _money;


                //this method will check that user have enough coins for bid he want to put
                this.checkCanPlay = value => {
                    if (_money >= value) return true;
                    return false;
                }

                //check value is ok
                this.changeWalletValue = (value, type = "+") => {
                    if (typeof value === "number" && !isNaN(value)) {
                        if (type === "+") {
                            return _money += value;
                        } else if (type === "-") {
                            return _money -= value;
                        } else {
                            throw new Error("Nieprawidłowy operator działania.")
                        }

                    } else {
                        console.log(typeof value);
                        throw new Error("Nieprawidłowa liczba.")
                    }
                }
            }
        }

        class Draw {
            constructor() {
                this.options = ['banana', 'plum', 'cherry'];
                let _result = this.drawResult()
                this.getDrawResult = () => _result;
            }

            drawResult() {
                let fruits = [];
                //this array will be filled with random choices pushes into it
                for (let i = 0; i < this.options.length; i++) {
                    const index = Math.floor(Math.random() * this.options.length)
                    const fruit = this.options[index]
                    // console.log(fruit);
                    fruits.push(fruit)
                }
                return fruits
            }
        }

        class Result {
            static moneyWinInGame(result, bid) {
                if (result) return 3 * ( Math.random() * (bid - 1) + 1);
                else return 0;
            }

            static checkWinner(draw) {
                if (
                    draw[0] === draw[1] && draw[1] === draw[2] ||
                    draw[0] !== draw[1] && draw[1] !== draw[2] && draw[0] !== draw[2]) return true;
                else return false
            }
        }

        class Statistics {
            constructor() {
                //this array is created for store game history from method below
                this.gameResults = []
            }
            addGameToStatistics(win, bid) {
                let gameResult = {
                    win: win,
                    bid: bid
                }
                this.gameResults.push(gameResult)
            }

            showGameStatistics() {
                let games = this.gameResults.length;
                let wins = this.gameResults.filter(result => result.win).length;
                let losses = this.gameResults.filter(result => !result.win).length
                return [games, wins, losses]
            }
        }

        class Game {
            constructor(amount = 200) {
                //open two instances
                this.stats = new Statistics();
                this.wallet = new Wallet(amount);

                document.getElementById('spin').addEventListener('click', this.startGame.bind(this));
                this.inputBid = document.getElementById('bid');
                //spans
                this.walletSaldo = document.querySelector('[data-summary="user-cash"]'); //money
                this.lastGame = document.querySelector('[data-summary="last-game"]'); //history of last game
                this.numberOfSpins = document.querySelector('[data-summary="spins"]'); //0 stats
                this.numberOfWins = document.querySelector('[data-summary="wins"]'); //1 stats
                this.numberOfLoses = document.querySelector('[data-summary="losses"]'); //2 stats
                //fruit divs converted into arr
                this.fruitsInMachine = [...document.querySelectorAll('[data-option="fruit"]')];
                //render method from below
                this.render()
            }

            //all this params are startup params - startGame method will upgrade them
            render(render = ['url(../images/bandit/dolar_start_sign.png)', 'url(../images/bandit/dolar_start_sign.png)', 'url(../images/bandit/dolar_start_sign.png)'],
                   money = this.wallet.getWalletValue(), stats = [0, 0, 0], lastGame = "", result = "", bid = 0) {

                if (result) {
                    document.querySelector('.summary p:nth-child(2)').style.color = "rgb(6, 214, 6)";
                    document.querySelector('.summary p:nth-child(2)').style.textShadow = "2px 5px 30px rgb(6, 214, 6);";
                    lastGame = `You win +${bid * 3}!`;
                } else if (!result && result !== "") {
                    document.querySelector('.summary p:nth-child(2)').style.color = "rgb(251, 17, 17)";
                    document.querySelector('.summary p:nth-child(2)').style.textShadow = "2px 5px 30px rgb(251, 17, 17);";
                    lastGame = `You lose -${bid}`
                }

                this.walletSaldo.textContent = money;
                this.lastGame.textContent = lastGame;
                this.numberOfSpins.textContent = stats[0];
                this.numberOfWins.textContent = stats[1];
                this.numberOfLoses.textContent = stats[2];
                this.fruitsInMachine.forEach((fruit, index) =>
                    fruit.style.backgroundImage = render[index]
                )
            }

            startGame() {

                if (this.inputBid.value < 1) return alert('Bid value cannot be lower than 1$.');
                //input value stored in var
                const bid = Math.floor(this.inputBid.value);

                if (!this.wallet.checkCanPlay(bid)) return alert("You don't have enough cash in wallet!")

                //method from Wallet class, substract cash after user spin
                this.wallet.changeWalletValue(bid, '-');
                //we open instance for the Draw class
                this.draw = new Draw();
                //now we assign one of the Draw method to the var
                const fruits = game.draw.getDrawResult()

                //maybe its look like noob-code, but it's working fine and rendering proper fruits

                //plum
                if (fruits[0] === 'plum') this.render[0] = this.fruitsInMachine[0].style.backgroundImage = 'url(../images/bandit/plum_transparent.png)'
                if (fruits[1] === 'plum') this.render[1] = this.fruitsInMachine[1].style.backgroundImage = 'url(../images/bandit/plum_transparent.png)'
                if (fruits[2] === 'plum') this.render[2] = this.fruitsInMachine[2].style.backgroundImage = 'url(../images/bandit/plum_transparent.png)'
                //banana
                if (fruits[0] === 'banana') this.render[0] = this.fruitsInMachine[0].style.backgroundImage = 'url(../images/bandit/banana_transparent.png)'
                if (fruits[1] === 'banana') this.render[1] = this.fruitsInMachine[1].style.backgroundImage = 'url(../images/bandit/banana_transparent.png)'
                if (fruits[2] === 'banana') this.render[2] = this.fruitsInMachine[2].style.backgroundImage = 'url(../images/bandit/banana_transparent.png)'
                //cherries
                if (fruits[0] === 'cherry') this.render[0] = this.fruitsInMachine[0].style.backgroundImage = 'url(../images/bandit/cherries_transparent.png)'
                if (fruits[1] === 'cherry') this.render[1] = this.fruitsInMachine[1].style.backgroundImage = 'url(../images/bandit/cherries_transparent.png)'
                if (fruits[2] === 'cherry') this.render[2] = this.fruitsInMachine[2].style.backgroundImage = 'url(../images/bandit/cherries_transparent.png)'

                //we get result from static method
                const result = Result.checkWinner(fruits);

                console.log("result=>", result, fruits)
                //another static method from Result class
                const wonMoney = Result.moneyWinInGame(result, bid);

                if (wonMoney) {
                    this.wallet.changeWalletValue(wonMoney, '+')
                }

                this.stats.addGameToStatistics(result, bid);

                //finaly when we get all information we overwrite it below
                this.render([fruits], this.wallet.getWalletValue(), this.stats.showGameStatistics(), this.wonMoney, result, bid);
            }

        }

        const game = new Game(this.money_amount);

    }
}
</script>
<style lang="scss">
h1 {
    font-family: 'Righteous', 'Arial';
    color: rgb(243, 247, 6);
    text-shadow: 2px 2px 30px rgb(243, 247, 6);
    text-align: center;
    padding-top: 25px;
    font-size: 35px;
}

.one-armed-bandit {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    padding: 10px 25px;
    box-sizing: border-box;
    background: #2c2c2d;
    border-radius: 10px;
}
.machine {
    position: relative;
    width: 100%;
    min-height: 200px;
    background-color: rgb(220, 24, 24);
    border-radius: 3%;
    box-shadow: 0px 0px 20px 3px rgb(243, 247, 6);
    animation: glowMachine ease infinite 5s;
}

.machine .cover {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    height: 90%;
    width: 95%;
    z-index: 1;
    background-color: rgba(0, 0, 0, 0.906);
    border-style: dotted;
    border-color: rgb(243, 247, 6);
    border-width: 25px;
    border-spacing: 50px;
    background-color: rgb(220, 24, 24);
}

.machine .cover .inside-cover {
    position: absolute;
    display: flex;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    height: 90%;
    width: 95%;
    z-index: 2;
    background-color: rgba(0, 0, 0, 0.906);
    overflow: hidden;
    justify-content: space-between;
    border: black 4px solid;
}

.screen-1,
.screen-2,
.screen-3 {
    width: 33%;
    height: 100%;
    background-color: rgb(246, 233, 213);
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    transition: 0.5s;
}

.screen-1 {
    background-image: url('./images/bandit/banana_transparent.png');
}

.screen-2 {
    background-image: url('./images/bandit/cherries_transparent.png');
}

.screen-3 {
    background-image: url('./images/bandit/plum_transparent.png');
}

.panel {
    padding: 10px 15px;
    position: relative;
    text-align: center;
    box-sizing: border-box;
    width: 100%;
}

.panel ::placeholder {
    text-align: center;
}

.panel input {
    text-align: center;
}

.spin {
    width: 100%;
    height: 70px;
    font-size: 34px;
    font-family: fantasy;
    /* font-kerning: unset; */
    border: none;
    cursor: pointer;
    background-color: rgb(6, 214, 6);
    box-shadow: 0px 0px 20px 3px rgb(6, 214, 6);
    transition: 0.5s;
    margin: 20px 0px 0px 0px;
    border-radius: 10px;
}

.spin:hover {
    background-color: black;
    color: rgb(6, 214, 6);
    box-shadow: 0px 0px 20px 10px rgb(6, 214, 6);
    text-shadow: 2px 5px 10px rgb(6, 214, 6);
}

input {
    width: 100%;
    height: 70px;
    border-radius: 10px;
    border: none;
    margin-top: 10px;
    font-size: 34px !important;
    font-family: "Righteous", "Arial";
}

.summary {
    position: relative;
    display: flex;
    justify-content: space-between;
    font-family: "Righteous", "Arial";
    align-items: center;
    flex-direction: column;
    /* top: 10%; */
    /* left: 50%; */
    /* transform: translateX(-50%); */
    color: rgb(6, 214, 6);
    text-shadow: 2px 5px 30px rgb(6, 214, 6);
    /* height: 150px; */
    width: 100%;

    span {
        font-weight: bold;
    }
}

.summary p:nth-child(2),
.summary p:nth-child(5) {
    color: rgb(251, 17, 17);
    text-shadow: 2px 5px 30px rgb(251, 17, 17);
}

.summary p:nth-child(3) {
    color: rgb(243, 247, 6);
    text-shadow: 2px 2px 30px rgb(243, 247, 6);
}

footer {
    position: fixed;
    display: flex;
    justify-content: space-around;
    bottom: 0%;
    left: 50%;
    transform: translateX(-50%);
    height: 30px;
    width: 800px;
    line-height: 30px;
}

footer h2 a {
    color: rgb(211, 34, 227);
    text-shadow: 2px 5px 30px rgb(211, 34, 227);
    font-family: 'Righteous', 'Arial';
    font-size: 20px;
    text-decoration: none;
}

footer p a {
    color: rgb(35, 206, 218);
    text-shadow: 2px 5px 30px rgb(35, 206, 218);
    font-size: 23px;
}

@keyframes glowMachine {

    0% {
        box-shadow: 0px 0px 20px 10px rgb(243, 247, 6);
    }

    25% {
        box-shadow: 0px 0px 20px 10px rgb(244, 4, 52);
    }

    50% {
        box-shadow: 0px 0px 20px 10px rgb(243, 247, 6);
    }

    75% {
        box-shadow: 0px 0px 20px 10px rgb(244, 4, 52);
    }

    100% {
        box-shadow: 0px 0px 20px 10px rgb(243, 247, 6);
    }
}
</style>
