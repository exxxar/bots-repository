<template>
    <div id="app">
        <h1>Игра: Выбери карточку</h1>
        <div class="game-grid">
            <div
                v-for="(card, index) in cards"
                :key="index"
                class="game-card"
                :class="{ flipped: card.flipped }"
                @click="revealCard(index)"
            >
                <span v-if="card.flipped">{{ card.display }}</span>
            </div>
        </div>
        <div class="info">
            <p>Ваши очки: {{ score }}</p>
            <p>Шанс хорошего приза: {{ goodPrizeChance }}%</p>
            <button @click="resetGame">Начать заново</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            gridSize: 5,
            goodPrizeChance: 2, // Шанс выиграть хороший приз в процентах
            cards: [],
            score: 0,
            prizes: [
                { id: 1, emoji: "🎉", description: "Большой праздник" },
                { id: 2, emoji: "🎁", description: "Подарок" },
                { id: 3, emoji: "🍀", description: "Удача" },
                { id: 4, emoji: "⭐", description: "Звезда" },
                { id: 5, emoji: "🏆", description: "Трофей" },
            ],
            bonuses: [
                { id: 1, emoji: "✨", description: "Искра" },
                { id: 2, emoji: "💫", description: "Звездопад" },
                { id: 3, emoji: "🔥", description: "Огонь" },
                { id: 4, emoji: "🌟", description: "Сияние" },
                { id: 5, emoji: "⚡", description: "Молния" },
            ],
        };
    },
    created() {
        this.initGame();
    },
    methods: {
        initGame() {
            this.score = 0;
            this.cards = Array.from({ length: this.gridSize * this.gridSize }, () => ({
                flipped: false,
                isGoodPrize: Math.random() < this.goodPrizeChance / 100,
                display: "",
                prize: null,
            }));
        },
        revealCard(index) {
            const card = this.cards[index];
            if (card.flipped) return;

            card.flipped = true;
            if (card.isGoodPrize) {
                const randomPrize = this.prizes[Math.floor(Math.random() * this.prizes.length)];
                card.display = randomPrize.emoji; // Случайный эмодзи для хорошего приза
                card.prize = randomPrize; // Сохраняем объект приза
                this.score += 50; // Например, 50 очков за хороший приз
            } else {
                const bonusPoints = Math.floor(Math.random() * 50) + 1;
                const randomBonus = this.bonuses[Math.floor(Math.random() * this.bonuses.length)];
                card.display = `${randomBonus.emoji} +${bonusPoints}`; // Случайный эмодзи и бонусные баллы
                card.prize = { ...randomBonus, points: bonusPoints }; // Сохраняем объект бонуса с очками
                this.score += bonusPoints;
            }
        },
        resetGame() {
            this.initGame();
        },
    },
};
</script>

<style>

.game-grid {
    display: grid;
    grid-template-columns: repeat(5, 50px);
    grid-gap: 5px;
    justify-content: center;
}
.game-card {
    width: 50px;
    height: 50px;
    background: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-weight: bold;
    border-radius: 5px;
    user-select: none;
    transition: background 0.3s;
}
.game-card.flipped {
    background: #28a745;
}
.info {
    margin-top: 20px;
}
button {
    padding: 10px 20px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}
button:hover {
    background: #0056b3;
}
</style>
