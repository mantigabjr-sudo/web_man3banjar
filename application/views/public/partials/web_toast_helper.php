<script>
// Sistem Floating Toast Modern Pengganti Popup "localhost says"
function showGlobalWebToast(message, type) {
    type = type || 'info';
    var bg = '#059669';
    var icon = 'bi-info-circle-fill';
    if (type === 'danger' || type === 'error') {
        bg = '#dc2626';
        icon = 'bi-exclamation-triangle-fill';
    } else if (type === 'warning') {
        bg = '#d97706';
        icon = 'bi-exclamation-circle-fill';
    } else if (type === 'success') {
        bg = '#16a34a';
        icon = 'bi-check-circle-fill';
    }

    var existing = document.getElementById('webGlobalToastContainer');
    if (!existing) {
        existing = document.createElement('div');
        existing.id = 'webGlobalToastContainer';
        existing.style.cssText = 'position:fixed; top:24px; right:20px; z-index:99999; display:flex; flex-direction:column; gap:10px; max-width:380px; width:calc(100% - 40px); pointer-events:none;';
        document.body.appendChild(existing);
    }

    var toast = document.createElement('div');
    toast.style.cssText = 'background:' + bg + '; color:#fff; padding:14px 18px; border-radius:14px; box-shadow:0 12px 35px rgba(0,0,0,0.2); display:flex; align-items:center; gap:12px; font-size:13.5px; font-weight:500; pointer-events:auto; border:1px solid rgba(255,255,255,0.2); animation:slideIn 0.3s ease;';
    toast.innerHTML = '<i class="bi ' + icon + '" style="font-size:20px; flex-shrink:0;"></i>' +
        '<div style="flex-grow:1; line-height:1.4;">' + message.replace(/\n/g, '<br>') + '</div>' +
        '<button type="button" style="background:none; border:none; color:#fff; font-size:18px; cursor:pointer; opacity:0.8; padding:0 4px; line-height:1;" onclick="this.parentElement.remove()">✕</button>';

    existing.appendChild(toast);
    setTimeout(function() {
        toast.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        setTimeout(function() { toast.remove(); }, 400);
    }, 4500);
}

// Override global window.alert
window.alert = function(msg) {
    showGlobalWebToast(msg, 'warning');
};
</script>
