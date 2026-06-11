package br.com.counter.mobile

import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.lifecycle.ViewModelProvider
import androidx.recyclerview.widget.LinearLayoutManager
import br.com.counter.mobile.databinding.ActivityCountItemsBinding

class CountItemsActivity : AppCompatActivity() {
    private lateinit var binding: ActivityCountItemsBinding
    private lateinit var viewModel: CountItemsViewModel
    private var countId = 0
    private val adapter = CountItemAdapter { item, quantity ->
        viewModel.saveQuantity(countId, item.id, quantity)
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityCountItemsBinding.inflate(layoutInflater)
        setContentView(binding.root)

        viewModel = ViewModelProvider(this)[CountItemsViewModel::class.java]
        countId = intent.getIntExtra("count_id", 0)
        val countStatus = intent.getStringExtra("count_status") ?: ""
        binding.textTitle.text = intent.getStringExtra("count_title") ?: "Contagem"
        binding.textStatus.text = statusLabel(countStatus)
        binding.textStatusLabel.text = statusLabel(countStatus)
        binding.viewStatusDot.background = ContextCompat.getDrawable(this, statusDot(countStatus))
        binding.recyclerItems.layoutManager = LinearLayoutManager(this)
        binding.recyclerItems.adapter = adapter

        binding.buttonBack.setOnClickListener {
            finish()
        }

        binding.buttonSync.setOnClickListener {
            viewModel.sync(countId)
        }

        binding.buttonSyncBottom.setOnClickListener {
            viewModel.sync(countId)
        }

        observe()
        viewModel.load(countId)
    }

    private fun observe() {
        viewModel.items.observe(this) {
            adapter.update(it)
            binding.layoutEmptyItems.visibility = if (it.isEmpty()) View.VISIBLE else View.GONE
        }

        viewModel.message.observe(this) {
            if (!it.isNullOrBlank()) {
                UiFeedback.showToast(this, it)
            }
        }

        viewModel.loading.observe(this) {
            binding.layoutLoadingItems.visibility = if (it) View.VISIBLE else View.GONE
            binding.recyclerItems.visibility = if (it) View.INVISIBLE else View.VISIBLE
            if (it) {
                binding.layoutEmptyItems.visibility = View.GONE
            }
            binding.buttonSyncBottom.isEnabled = !it
            binding.buttonBack.isEnabled = !it
            binding.buttonSyncBottom.text = if (it) "Sincronizando" else "Sincronizar"
        }

        viewModel.savingItemId.observe(this) {
            adapter.updateSavingItem(it)
        }
    }

    private fun statusLabel(status: String): String {
        return when (status) {
            "open" -> "Aberta"
            "in_progress" -> "Em andamento"
            "finished" -> "Finalizada"
            "approved" -> "Aprovada"
            else -> status
        }
    }

    private fun statusDot(status: String): Int {
        return when (status) {
            "open" -> R.drawable.bg_status_open
            "in_progress" -> R.drawable.bg_status_progress
            "finished" -> R.drawable.bg_status_finished
            "approved" -> R.drawable.bg_status_approved
            else -> R.drawable.bg_status_finished
        }
    }
}
