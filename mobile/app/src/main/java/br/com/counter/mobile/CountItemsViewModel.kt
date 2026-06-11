package br.com.counter.mobile

import android.app.Application
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.viewModelScope
import kotlinx.coroutines.launch

class CountItemsViewModel(application: Application) : AndroidViewModel(application) {
    private val repository = InventoryRepository(application.applicationContext)
    private val itemsValue = MutableLiveData<List<CountItemEntity>>()
    private val messageValue = MutableLiveData<String?>()
    private val syncedValue = MutableLiveData(false)

    val items: LiveData<List<CountItemEntity>> = itemsValue
    val message: LiveData<String?> = messageValue
    val synced: LiveData<Boolean> = syncedValue

    fun load(countId: Int) {
        viewModelScope.launch {
            itemsValue.value = repository.localItems(countId)
            val result = repository.refreshItems(countId)

            if (result.isSuccess) {
                itemsValue.value = result.getOrNull()
            } else {
                messageValue.value = result.exceptionOrNull()?.message
            }
        }
    }

    fun saveQuantity(countId: Int, itemId: Int, value: String) {
        viewModelScope.launch {
            messageValue.value = null
            val quantity = value.replace(",", ".").toDoubleOrNull()
            repository.saveQuantity(itemId, quantity)
            itemsValue.value = repository.localItems(countId)
            messageValue.value = "Quantidade salva localmente."
        }
    }

    fun sync(countId: Int) {
        viewModelScope.launch {
            messageValue.value = null
            val result = repository.syncItems(countId)

            if (result.isSuccess) {
                syncedValue.value = true
                itemsValue.value = repository.localItems(countId)
                messageValue.value = "Itens sincronizados com sucesso."
            } else {
                messageValue.value = result.exceptionOrNull()?.message
            }
        }
    }
}
