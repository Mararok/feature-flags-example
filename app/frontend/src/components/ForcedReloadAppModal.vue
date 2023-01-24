<script setup lang="ts">
function reload() {
    window.location.reload();
}
</script>


<template>
    <div class="modal-overlay">
        <div class="modal">
            <h6>Some experimental features have been disabled or others have been enabled as required by the application
              reload</h6>
            <button @click="reload">Reload app</button>
        </div>
    </div>
</template>
  

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    background-color: #000000da;
    z-index: 100000;
}

.modal {
    text-align: center;
    background-color: white;
    height: 500px;
    width: 500px;
    margin-top: 10%;
    padding: 60px 0;
    border-radius: 20px;
}

.check {
    width: 150px;
}

h6 {
    font-weight: 500;
    font-size: 28px;
    margin: 20px 0;
}

p {
    font-size: 16px;
    margin: 20px 0;
}

button {
    background-color: #ac003e;
    width: 150px;
    height: 40px;
    color: white;
    font-size: 14px;
    border-radius: 16px;
    margin-top: 50px;
}

button:hover {
    background-color: #a8e61a;
    width: 150px;
    height: 40px;
    color: white;
    font-size: 14px;
    border-radius: 16px;
    margin-top: 50px;
    cursor: pointer;
}
</style>
