document.addEventListener("DOMContentLoaded", () => {
    const voucherInput = document.getElementById('voucher_path_2');
    const voucherBtn = document.getElementById('btnUploadVoucher_2');
    const voucherLabel = document.getElementById('voucher_label_2');

    function limpiarErroresCampo(campo) {
        if (!campo) return;
        
        campo.classList.remove('border-red-500', 'focus:ring-red-500');

        const parentDiv = campo.closest('.flex-col') || campo.parentElement;
        if (parentDiv) {
            const errorContainer = parentDiv.querySelector('div[class*="min-h"]');
            if (errorContainer) {
                const erroresExistentes = errorContainer.querySelectorAll('p.text-red-500');
                erroresExistentes.forEach(err => err.remove());
            }
        }
        
        const borderContainer = campo.closest('.flex.items-center.rounded-lg.border');
        if (borderContainer) {
            borderContainer.classList.remove('border-red-500');
        }
    }

    if (voucherBtn && voucherInput) {
        voucherBtn.addEventListener("click", () => voucherInput.click());

        voucherInput.addEventListener("change", () => {
            if (voucherInput.files.length > 0) {
                voucherLabel.textContent = voucherInput.files[0].name;
                voucherLabel.classList.remove("text-gray-500");
                voucherLabel.classList.add("text-green-600", "font-semibold");
                
                limpiarErroresCampo(voucherInput);
            } else {
                voucherLabel.textContent = "Seleccionar archivo";
                voucherLabel.classList.remove("text-green-600", "font-semibold");
                voucherLabel.classList.add("text-gray-500");
            }
        });
    }

    let metodo2 = document.getElementById('metodo_pago_2');
    let recibo2 = document.getElementById('detalle_recibo_2');
    let monto2 = document.getElementById('detalle_monto_2');
    const montoTotalInput = document.getElementById('monto_total_a_pagar');
    const montoPagadoInput = document.getElementById('monto_pagado');
    const voucherGroup2 = document.getElementById('voucher_group_2');
    const form = document.getElementById('form');

    if (!metodo2 || !recibo2 || !monto2) {
        return;
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            console.log("🟡 Formulario enviándose...");
            console.log("🟡 Valores del formulario:", {
                metodo_pago: metodo2.value,
                detalle_recibo: recibo2.value,
                detalle_monto: monto2.value,
                detalle_fecha: document.getElementById('detalle_fecha_2').value
            });
        });
    }

    function aplicarValidacionOperacion(inputEl, metodo) {
        if (!inputEl) return null;
        
        const nuevo = inputEl.cloneNode(true);
        inputEl.parentNode.replaceChild(nuevo, inputEl);
        inputEl = nuevo;

        inputEl.addEventListener('input', function(e) {
            let val = e.target.value || '';
            let maxLength = 0;
            const metodolower = (metodo || '').toLowerCase();
            
            if (!metodo || metodo === '' || metodolower === 'seleccionar' || metodolower === 'seleccione...') {
                e.target.value = '';
                return;
            }
            
            switch(metodolower) {
                case 'yape':
                case 'plin':
                    val = val.replace(/[^0-9]/g,''); 
                    maxLength = 8; 
                    break;
                case 'transferencia':
                    val = val.replace(/[^0-9]/g,''); 
                    maxLength = 12; 
                    break;
                case 'tarjeta':
                    val = val.replace(/[^0-9]/g,''); 
                    maxLength = 16; 
                    break;
                case 'paypal':
                    val = val.replace(/[^A-Za-z0-9]/g,''); 
                    maxLength = 17; 
                    break;
                default:
                    val = val.replace(/[^A-Za-z0-9]/g,''); 
                    maxLength = 17;
            }
            
            if (maxLength && val.length > maxLength) {
                val = val.substring(0, maxLength);
            }
            
            e.target.value = val;
            
            if (val.length > 0) {
                limpiarErroresCampo(e.target);
            }
        });
        
        return inputEl;
    }

    // Validación de decimales para monto
    function validarDecimales(inputEl) {
        if (!inputEl) return null;
        
        const nuevo = inputEl.cloneNode(true);
        inputEl.parentNode.replaceChild(nuevo, inputEl);
        inputEl = nuevo;

        inputEl.addEventListener('input', function(e) {
            let v = e.target.value || '';
            v = v.replace(/[^0-9.]/g,'');
            const parts = v.split('.');
            if (parts.length > 2) v = parts[0] + '.' + parts.slice(1).join('');
            if (parts[1] && parts[1].length > 2) v = parts[0] + '.' + parts[1].slice(0,2);
            e.target.value = v;
            
            limpiarErroresCampo(e.target);
        });

        inputEl.addEventListener('blur', function(e) {
            const num = parseFloat(e.target.value);
            const montoTotal = parseFloat(montoTotalInput.value) || 0;
            const montoPago1 = parseFloat(montoPagadoInput.value) || 0;
            const montoRestante = montoTotal - montoPago1;
            
            if (!isNaN(num)) {
                if (num > montoRestante) {
                    e.target.value = montoRestante.toFixed(2);
                } else {
                    e.target.value = num.toFixed(2);
                }
            } else if (e.target.value !== '') {
                e.target.value = '';
            }
        });

        return inputEl;
    }

    // Limpiar errores al escribir
    recibo2.addEventListener('input', function() {
        if (this.value.length > 0) {
            limpiarErroresCampo(this);
        }
    });
    
    monto2.addEventListener('input', function() {
        if (this.value.length > 0) {
            limpiarErroresCampo(this);
        }
    });
    
    const fecha2 = document.getElementById('detalle_fecha_2');
    if (fecha2) {
        fecha2.addEventListener('change', function() {
            if (this.value.length > 0) {
                limpiarErroresCampo(this);
            }
        });
        
        fecha2.addEventListener('input', function() {
            if (this.value.length > 0) {
                limpiarErroresCampo(this);
            }
        });
    }
    
    metodo2.addEventListener('change', function() {
        if (this.value !== '' && this.value !== 'Seleccionar' && this.value !== 'Seleccione...') {
            limpiarErroresCampo(this);
        }
    });

    // Evento principal: cuando cambia el método de pago
    metodo2.addEventListener('change', function() {
        const metodoSeleccionado = (this.value || '').toLowerCase();
        
        if (!metodoSeleccionado || metodoSeleccionado === '' || metodoSeleccionado === 'seleccione...') {
            recibo2.value = '';
            recibo2.classList.add('bg-gray-100', 'dark:bg-gray-800', 'cursor-not-allowed');
            recibo2.setAttribute('data-blocked', 'true');
            
            if (voucherGroup2) {
                voucherGroup2.classList.add('hidden');
            }
        } else {
            recibo2.classList.remove('bg-gray-100', 'dark:bg-gray-800', 'cursor-not-allowed');
            recibo2.removeAttribute('data-blocked');
            
            recibo2 = aplicarValidacionOperacion(recibo2, metodoSeleccionado);
            
            if (voucherGroup2) {
                if (['yape', 'transferencia'].includes(metodoSeleccionado)) {
                    voucherGroup2.classList.remove('hidden');
                } else {
                    voucherGroup2.classList.add('hidden');
                    if (voucherInput) voucherInput.value = '';
                    if (voucherLabel) voucherLabel.textContent = 'Seleccionar archivo';
                }
            }
        }
    });

    recibo2.addEventListener('keydown', function(e) {
        if (this.getAttribute('data-blocked') === 'true') {
            e.preventDefault();
        }
    });

    recibo2.addEventListener('paste', function(e) {
        if (this.getAttribute('data-blocked') === 'true') {
            e.preventDefault();
        }
    });

    monto2 = validarDecimales(monto2);

    const metodoActual = metodo2.value || '';
    if (metodoActual && metodoActual !== '' && metodoActual !== 'Seleccione...') {
        recibo2.classList.remove('bg-gray-100', 'dark:bg-gray-800', 'cursor-not-allowed');
        recibo2.removeAttribute('data-blocked');
        
        recibo2 = aplicarValidacionOperacion(recibo2, metodoActual.toLowerCase());
        
        if (voucherGroup2 && ['yape', 'transferencia'].includes(metodoActual.toLowerCase())) {
            voucherGroup2.classList.remove('hidden');
        }
    } else {
        recibo2.classList.add('bg-gray-100', 'dark:bg-gray-800', 'cursor-not-allowed');
        recibo2.setAttribute('data-blocked', 'true');
    }
});
