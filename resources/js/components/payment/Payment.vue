<template>
  <div>
    <button @click="payNow">Bayar Sekarang</button>
  </div>
</template>

<script>
export default {
  props: ['courseId'],
  methods: {
    async payNow() {
      try {
        // 1. Kirim request ke Laravel (API backend)
        const res = await fetch('http://localhost:8000/api/checkout', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${localStorage.getItem('token')}` // jika pakai token
          },
          body: JSON.stringify({
            course_id: this.courseId
          })
        });

        const data = await res.json();

        // 2. Panggil Midtrans Snap UI
        window.snap.pay(data.snap_token, {
          onSuccess: function(result) {
            alert('Pembayaran sukses!');
            console.log(result);
          },
          onPending: function(result) {
            alert('Menunggu pembayaran!');
            console.log(result);
          },
          onError: function(result) {
            alert('Pembayaran gagal!');
            console.log(result);
          }
        });

      } catch (error) {
        console.error('Checkout error:', error);
        alert('Gagal memulai pembayaran');
      }
    }
  }
}
</script>

<!-- Tambahkan di index.html atau public/index.html -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="Mid-client-UyQhhqiodzZuSBpA"></script>
