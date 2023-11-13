SELECT
        r.id,
        r.request_no,
        r.unique_sku_id,
        r.quality_name,
        r.design_name,
        r.shade_name,
        r.delivery_date,
        r.barcode,
        r.requirement,
        l.location_name,
        l.id as location_id,
        r.print_design,
        r.print_colorway,
        r.emb_design,
        r.emb_colorway,
        r.emb_vendor,
        COALESCE(i.total_quantity, 0) as total_quantity,
        COALESCE(o.total_outward_quantity, 0) as total_outward_quantity,
        COALESCE(i.total_quantity - o.total_outward_quantity, 0) as available_quantity
    FROM requests r
    LEFT JOIN (
        SELECT
            inward.request_id,
            inward.unique_sku_id,
            inward.location_id,
            COALESCE(SUM(inward.quantity), 0) as total_quantity
        FROM inward
        INNER JOIN location_master l ON inward.location_id = l.id
        GROUP BY inward.request_id, inward.unique_sku_id, inward.location_id
    ) i ON r.id = i.request_id AND r.unique_sku_id = i.unique_sku_id
    LEFT JOIN (
        SELECT
            request_id,
            unique_sku_id,
            location_id,
            COALESCE(SUM(outward.issued_quantity), 0) as total_outward_quantity
        FROM outward
        GROUP BY request_id, unique_sku_id, location_id
        
    ) o ON r.id = o.request_id AND r.unique_sku_id = o.unique_sku_id AND i.location_id = o.location_id 
     LEFT JOIN (
        SELECT
        location_master.location_name,
        location_master.id
        FROM location_master        
    ) l ON i.location_id = l.id  

    ORDER BY r.request_date;




    return  DB::table('requests as r')
            ->select(
                'r.id',
                'r.request_no',
                'r.unique_sku_id',
                'r.quality_name',
                'r.design_name',
                'r.shade_name',
                'r.delivery_date',
                'r.barcode',
                'r.requirement',
                'l.location_name',
                'l.id as location_id',
                'r.print_design',
                'r.print_colorway',
                'r.emb_design',
                'r.emb_colorway',
                'r.emb_vendor',
                DB::raw('COALESCE(i.total_quantity, 0) as total_quantity'),
                DB::raw('COALESCE(o.total_outward_quantity, 0) as total_outward_quantity'),
                DB::raw('COALESCE(i.total_quantity - o.total_outward_quantity, 0) as available_quantity')
            )
            ->leftJoin(DB::raw('
           (SELECT inward.request_id, inward.unique_sku_id, inward.location_id, COALESCE(SUM(inward.quantity), 0) as total_quantity
            FROM inward
            INNER JOIN location_master l ON inward.location_id = l.id
            GROUP BY inward.request_id, inward.unique_sku_id, inward.location_id) as i'), function ($join) {
                $join->on('r.id', '=', DB::raw('i.request_id'))
                    ->on('r.unique_sku_id', '=', DB::raw('i.unique_sku_id'));
            })
            ->leftJoin(DB::raw('
           (SELECT request_id, unique_sku_id, location_id, COALESCE(SUM(outward.issued_quantity), 0) as total_outward_quantity
            FROM outward
            GROUP BY request_id, unique_sku_id, location_id) as o'), function ($join) {
                $join->on('r.id', '=', DB::raw('o.request_id'))
                    ->on('r.unique_sku_id', '=', DB::raw('o.unique_sku_id'));
            })
            ->leftJoin(DB::raw('
           (SELECT location_master.location_name, location_master.id
            FROM location_master) as l'), function ($join) {
                $join->on(DB::raw('i.location_id'), '=', DB::raw('l.id'));
            })
            ->orderBy("r.request_date")
            ->get();