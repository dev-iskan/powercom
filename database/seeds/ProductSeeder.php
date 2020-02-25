<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product1 = \App\Models\Products\Product::create([
            'name' => 'Лампа сигнальная EKF mdla-47-y-pro (желтая)',
            'short_description' => 'Дополнительные устройства PROxima – это новое поколение устройств, в которые внедрены все самые нновационные разработки в области электротехники. Образцы данной серии имеют уникальный дизайн и множество преимуществ перед изделиями предыдущих серий.',
            'description' => 'Дополнительные устройства PROxima – это новое поколение устройств, в которые внедрены все самые нновационные разработки в области электротехники. Образцы данной серии имеют уникальный дизайн и множество преимуществ перед изделиями предыдущих серий.',
            'quantity'=> 100,
            'price' =>  13800,
            'active' => true,
            'order' => 1
        ]);
        $product1->brand()->associate(1)->save();
        $product1->categories()->sync([1]);
    
        $product2 = \App\Models\Products\Product::create([
            'name' => 'Стабилизатор напряжения напольный ANDELI 15 KVA 110-250 вольт',
            'short_description' => 'Релейный стабилизатор напряжения ANDELI SVC-D15000VA 15kVA предназначен для защиты электроприборов и устройств от сбоев в бытовой сети. Устройство эффективно поддерживает постоянное стабильное напряжение в пределах допустимой нормы. Корпус стабилизатора имеет большое количество отверстий, способствующих естественной вентиляции воздуха, что предотвращает перегрев внутренних узлов. Устройство отлично впишется в любой интерьер благодаря современному дизайну и навесному исполнению. Купить стабилизатор ANDELI можно купить с гарантией 1 год на сайте 220 вольт.',
            'description' => 'Релейный стабилизатор напряжения ANDELI SVC-D15000VA 15kVA предназначен для защиты электроприборов и устройств от сбоев в бытовой сети. Устройство эффективно поддерживает постоянное стабильное напряжение в пределах допустимой нормы. Корпус стабилизатора имеет большое количество отверстий, способствующих естественной вентиляции воздуха, что предотвращает перегрев внутренних узлов. Устройство отлично впишется в любой интерьер благодаря современному дизайну и навесному исполнению. Купить стабилизатор ANDELI можно купить с гарантией 1 год на сайте 220 вольт.',
            'quantity'=> 10,
            'price' =>  2074750,
            'active' => true,
            'order' => 2
        ]);
        $product2->brand()->associate(2)->save();
        $product2->categories()->sync([2]);
    
        $product3 = \App\Models\Products\Product::create([
            'name' => 'Контактор ANDELI CJX2-D0910 220V, 380V',
            'short_description' => 'Контактор переменного тока серии CJX2 подходит для использования в цепях с номинальным напряжением до 660 В переменного тока 50/60 Гц, номинальным током до 620А, для замыкания, размыкания, частого запуска и управления двигателем переменного тока. При использовании с дополнительным контактным блоком, таймером и устройством блокировки машины и т. д., устройство выполняет функцию контактора задержки, механического контактора блокировки, контактора переключения со звезды на треугольник. Вместе с тепловым реле устройство составляет электромагнитный стартер. Устройство соответствует стандарту IEC60947-4-1.',
            'description' => 'Контактор переменного тока серии CJX2 подходит для использования в цепях с номинальным напряжением до 660 В переменного тока 50/60 Гц, номинальным током до 620А, для замыкания, размыкания, частого запуска и управления двигателем переменного тока. При использовании с дополнительным контактным блоком, таймером и устройством блокировки машины и т. д., устройство выполняет функцию контактора задержки, механического контактора блокировки, контактора переключения со звезды на треугольник. Вместе с тепловым реле устройство составляет электромагнитный стартер. Устройство соответствует стандарту IEC60947-4-1.',
            'quantity'=> 50,
            'price' =>  48250,
            'active' => true,
            'order' => 3
        ]);
        $product3->brand()->associate(2)->save();
        $product3->categories()->sync([2]);
    
        $product4 = \App\Models\Products\Product::create([
            'name' => 'Прожектор светодиодный 100W 7070',
            'short_description' => 'Светодиодный прожектор 50W оптимальное решение для освещения улиц, зданий, мостов, рекламных щитов, парковок, складских помещений и прилегающих территорий. Светодиодный прожектор 50W дают яркий направленный свет, при этом максимально экономят энергию, имеют долгий срок службы, высокую степень защиты от механических повреждений, воздействия окружающей среды и не требуют дополнительнх затрат на обслуживание. Корпус светодиодных прожекторов имеет специальное антикоррозийное порошковое покрытие, обеспечивающее надёжную защиту от механических повреждений и воздействий окружающей среды. Источник питания расположен внутри алюминиевого корпуса и обеспечивает надёжную стабильную работу прожектора.',
            'description' => 'Светодиодный прожектор 50W оптимальное решение для освещения улиц, зданий, мостов, рекламных щитов, парковок, складских помещений и прилегающих территорий. Светодиодный прожектор 50W дают яркий направленный свет, при этом максимально экономят энергию, имеют долгий срок службы, высокую степень защиты от механических повреждений, воздействия окружающей среды и не требуют дополнительнх затрат на обслуживание. Корпус светодиодных прожекторов имеет специальное антикоррозийное порошковое покрытие, обеспечивающее надёжную защиту от механических повреждений и воздействий окружающей среды. Источник питания расположен внутри алюминиевого корпуса и обеспечивает надёжную стабильную работу прожектора.',
            'quantity'=> 50,
            'price' =>  48250,
            'active' => true,
            'order' => 3
        ]);
        $product4->save();
        $product4->categories()->sync([3]);
    }
}
